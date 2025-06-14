import joblib
import numpy as np
import xgboost as xgb

# Use a try-except block for compatibility between local dev and Heroku
try:
    # This will work locally if you have tensorflow installed
    import tensorflow as tf
    Interpreter = tf.lite.Interpreter
except (ImportError, AttributeError):
    # This will work on Heroku with tflite_runtime
    from tflite_runtime.interpreter import Interpreter  # type: ignore

import os
import json
from classifier_config import (
    MODEL_CONFIG,
    CLASSIFICATION_CONFIG
)

class LearningStyleClassifier:
    def __init__(self):
        self.style_mapping = {
            0: 'activist',
            1: 'reflector',
            2: 'theorist',
            3: 'pragmatist'
        }
        
        # Define model paths
        self.model_path = 'models'
        self.model_files = {
            'decision_tree': 'decision_tree.joblib',
            'random_forest': 'random_forest.joblib',
            'support_vector_machine': 'support_vector_machine.joblib',
            'logistic_regression': 'logistic_regression.joblib',
            'xgboost': 'xgboost.json',
            'blending_meta_learner': 'blending_meta_learner.joblib',
            'cnn': 'cnn_model.tflite',
            'metrics': 'model_metrics.json'
        }
        
        # Load all models and metrics
        self._load_models_and_metrics()

    def _load_models_and_metrics(self):
        """Loads all pre-trained models and metrics from disk."""
        self.classifiers = {}
        self.model_metrics = {}
        for name, filename in self.model_files.items():
            path = os.path.join(self.model_path, filename)
            if name == 'cnn':
                self.cnn_interpreter = Interpreter(model_path=path)
                self.cnn_interpreter.allocate_tensors()
                self.cnn_input_details = self.cnn_interpreter.get_input_details()
                self.cnn_output_details = self.cnn_interpreter.get_output_details()
            elif name == 'xgboost':
                clf = xgb.XGBClassifier()
                clf.load_model(path)
                # Manually set attributes to be scikit-learn compatible
                clf.classes_ = np.array([0, 1, 2, 3])
                clf.n_classes_ = 4
                self.classifiers[name] = clf
            elif name == 'blending_meta_learner':
                self.blending_meta_learner = joblib.load(path)
            elif name == 'metrics':
                with open(path, 'r') as f:
                    self.model_metrics = json.load(f)
            else:
                self.classifiers[name] = joblib.load(path)

    def predict(self, answers):
        X = np.array(answers, dtype=np.float32).reshape(1, -1)
        
        predictions = {}
        
        # Base classifier predictions
        for name, clf in self.classifiers.items():
            predictions[name] = int(clf.predict(X)[0])
        
        # CNN prediction
        self.cnn_interpreter.set_tensor(self.cnn_input_details[0]['index'], X.reshape(1, 80))
        self.cnn_interpreter.invoke()
        cnn_proba = self.cnn_interpreter.get_tensor(self.cnn_output_details[0]['index'])
        predictions['cnn'] = int(np.argmax(cnn_proba))
        
        # Blending ensemble prediction
        base_models_for_blending = MODEL_CONFIG['blending_ensemble']['base_models']
        base_predictions_proba = np.column_stack([
            self.classifiers[model_name].predict_proba(X) for model_name in base_models_for_blending
        ])
        predictions['blending_ensemble'] = int(self.blending_meta_learner.predict(base_predictions_proba)[0])
        
        # Apply weights to votes to determine the final style
        weighted_votes = {}
        for style_id in range(4): # For each of the 4 styles
            weighted_votes[style_id] = sum(
                CLASSIFICATION_CONFIG['style_weights'].get(classifier_name, 0)
                for classifier_name, pred_id in predictions.items()
                if pred_id == style_id
            )
        
        voted_style_id = max(weighted_votes, key=weighted_votes.get)
        predicted_style_name = self.style_mapping[voted_style_id]
        
        # Calculate confidence
        total_weight_for_voted_style = weighted_votes[voted_style_id]
        total_weight_of_all_models = sum(CLASSIFICATION_CONFIG['style_weights'].values())
        confidence = total_weight_for_voted_style / total_weight_of_all_models if total_weight_of_all_models > 0 else 0

        # Format individual votes for the response
        individual_votes = {
            name: self.style_mapping[pred_id]
            for name, pred_id in predictions.items()
        }
        
        result = {
            'learning_style': predicted_style_name,
            'confidence': confidence,
            'individual_votes': individual_votes,
            'model_metrics': self.model_metrics
        }
        
        if confidence < CLASSIFICATION_CONFIG.get('confidence_threshold', 0.5):
            result['warning'] = 'Low confidence prediction'
            
        return result 