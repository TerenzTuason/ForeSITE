from sklearn.tree import DecisionTreeClassifier
from sklearn.svm import SVC
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import accuracy_score, f1_score, precision_score, recall_score, roc_auc_score
from sklearn.preprocessing import LabelBinarizer
import xgboost as xgb
import tensorflow as tf
import numpy as np
import time
from classifier_config import (
    STYLE_QUESTIONS,
    MODEL_CONFIG,
    TRAINING_CONFIG,
    CLASSIFICATION_CONFIG,
    QUESTION_WEIGHTS
)

class LearningStyleClassifier:
    def __init__(self):
        self.style_mapping = {
            0: 'activist',
            1: 'reflector',
            2: 'theorist',
            3: 'pragmatist'
        }
        
        # Initialize metrics storage
        self.model_metrics = {}
        
        # Initialize individual classifiers with config parameters
        self.classifiers = {
            'decision_tree': DecisionTreeClassifier(**MODEL_CONFIG['decision_tree']),
            'random_forest': RandomForestClassifier(**MODEL_CONFIG['random_forest']),
            'support_vector_machine': SVC(**MODEL_CONFIG['support_vector_machine'], probability=True),
            'logistic_regression': LogisticRegression(**MODEL_CONFIG['logistic_regression']),
            'xgboost': xgb.XGBClassifier(**MODEL_CONFIG['xgboost']),
        }
        
        # Initialize CNN model
        self.cnn_model = self._build_cnn_model()
        
        # Initialize blending ensemble
        self.blending_meta_learner = LogisticRegression(**MODEL_CONFIG['blending_ensemble']['meta_learner_params'])
        
        # Pre-train the models
        self._pretrain_models()

    def _build_cnn_model(self):
        model = tf.keras.Sequential()
        
        # Reshape layer for 1D input
        model.add(tf.keras.layers.Reshape((80, 1), input_shape=(80,)))
        
        # Add convolutional layers
        for conv_layer in MODEL_CONFIG['cnn']['conv_layers']:
            model.add(tf.keras.layers.Conv1D(
                filters=conv_layer['filters'],
                kernel_size=conv_layer['kernel_size'],
                activation='relu'
            ))
            model.add(tf.keras.layers.MaxPooling1D(2))
        
        model.add(tf.keras.layers.Flatten())
        
        # Add dense layers
        for units in MODEL_CONFIG['cnn']['dense_layers']:
            model.add(tf.keras.layers.Dense(units, activation='relu'))
            model.add(tf.keras.layers.Dropout(0.2))
        
        # Output layer
        model.add(tf.keras.layers.Dense(4, activation='softmax'))
        
        model.compile(
            optimizer=tf.keras.optimizers.Adam(learning_rate=MODEL_CONFIG['cnn']['learning_rate']),
            loss='sparse_categorical_crossentropy',
            metrics=['accuracy']
        )
        
        return model

    def _calculate_metrics(self, y_true, y_pred, y_pred_proba, model_name, training_time):
        lb = LabelBinarizer()
        y_true_bin = lb.fit_transform(y_true)
        
        metrics = {
            'accuracy': accuracy_score(y_true, y_pred),
            'f1_score': f1_score(y_true, y_pred, average='weighted'),
            'precision': precision_score(y_true, y_pred, average='weighted'),
            'recall': recall_score(y_true, y_pred, average='weighted'),
            'specificity': np.mean([
                1 - ((1 - y_true_bin[:, i]) @ y_pred_proba[:, i]) / np.sum(1 - y_true_bin[:, i])
                for i in range(4)
            ]),
            'auc_roc': roc_auc_score(y_true_bin, y_pred_proba, average='weighted', multi_class='ovr'),
            'training_time': training_time
        }
        
        self.model_metrics[model_name] = metrics
        return metrics

    def _pretrain_models(self):
        # Create training data
        X_train, y_train = self._generate_training_data()
        
        # Train and evaluate each model
        for name, clf in self.classifiers.items():
            start_time = time.time()
            clf.fit(X_train, y_train)
            training_time = time.time() - start_time
            
            y_pred = clf.predict(X_train)
            y_pred_proba = clf.predict_proba(X_train)
            self._calculate_metrics(y_train, y_pred, y_pred_proba, name, training_time)
        
        # Train and evaluate CNN
        start_time = time.time()
        self.cnn_model.fit(
            X_train, y_train,
            epochs=MODEL_CONFIG['cnn']['epochs'],
            batch_size=MODEL_CONFIG['cnn']['batch_size'],
            verbose=0
        )
        training_time = time.time() - start_time
        
        y_pred = np.argmax(self.cnn_model.predict(X_train), axis=1)
        y_pred_proba = self.cnn_model.predict(X_train)
        self._calculate_metrics(y_train, y_pred, y_pred_proba, 'cnn', training_time)
        
        # Train and evaluate blending ensemble
        start_time = time.time()
        base_predictions = np.column_stack([
            self.classifiers[model_name].predict_proba(X_train)
            for model_name in MODEL_CONFIG['blending_ensemble']['base_models']
        ])
        
        self.blending_meta_learner.fit(base_predictions, y_train)
        training_time = time.time() - start_time
        
        y_pred = self.blending_meta_learner.predict(base_predictions)
        y_pred_proba = self.blending_meta_learner.predict_proba(base_predictions)
        self._calculate_metrics(y_train, y_pred, y_pred_proba, 'blending_ensemble', training_time)

    def _generate_training_data(self):
        # Convert question indices to 0-based indexing
        style_questions = {
            style: [q - 1 for q in questions]
            for style, questions in STYLE_QUESTIONS.items()
        }
        
        def create_pattern(primary_style, secondary_style=None):
            pattern = np.zeros(80)
            pattern[style_questions[primary_style]] = 1
            
            if secondary_style:
                secondary_indices = np.random.choice(
                    style_questions[secondary_style],
                    size=int(len(style_questions[secondary_style]) * TRAINING_CONFIG['secondary_style_ratio']),
                    replace=False
                )
                pattern[secondary_indices] = 1
            
            noise_indices = np.random.choice(
                [i for i in range(80) if i not in style_questions[primary_style]],
                size=int(80 * TRAINING_CONFIG['noise_level']),
                replace=False
            )
            pattern[noise_indices] = 1
            
            return self._apply_question_weights(pattern, primary_style)
        
        X_train = []
        y_train = []
        
        style_pairs = [
            ('activist', 'pragmatist'),
            ('reflector', 'theorist'),
            ('theorist', 'reflector'),
            ('pragmatist', 'activist')
        ]
        
        for _ in range(TRAINING_CONFIG['samples_per_style']):
            for i, (primary, secondary) in enumerate(style_pairs):
                X_train.append(create_pattern(primary, secondary))
                y_train.append(i)
        
        return np.array(X_train), np.array(y_train)

    def _apply_question_weights(self, pattern, style):
        """Apply weights to certain questions based on their importance"""
        weighted_pattern = pattern.copy()
        primary_questions = QUESTION_WEIGHTS[style.lower()]['primary']
        weight = QUESTION_WEIGHTS[style.lower()]['weight']
        
        # Convert to 0-based indexing
        primary_questions = [q - 1 for q in primary_questions]
        
        # Apply weights to primary questions
        weighted_pattern[primary_questions] *= weight
        return weighted_pattern

    def predict(self, answers):
        X = np.array(answers).reshape(1, -1)
        
        # Get predictions from each classifier
        predictions = {}
        probabilities = {}
        
        for name, clf in self.classifiers.items():
            predictions[name] = clf.predict(X)[0]
            probabilities[name] = clf.predict_proba(X)
        
        # CNN predictions
        cnn_proba = self.cnn_model.predict(X)
        predictions['cnn'] = np.argmax(cnn_proba)
        probabilities['cnn'] = cnn_proba
        
        # Blending ensemble predictions
        base_predictions = np.column_stack([
            self.classifiers[model_name].predict_proba(X)
            for model_name in MODEL_CONFIG['blending_ensemble']['base_models']
        ])
        predictions['blending_ensemble'] = self.blending_meta_learner.predict(base_predictions)[0]
        probabilities['blending_ensemble'] = self.blending_meta_learner.predict_proba(base_predictions)
        
        # Apply weights to votes
        weighted_votes = {}
        for style_id in range(4):
            weighted_votes[style_id] = sum(
                CLASSIFICATION_CONFIG['style_weights'][classifier_name]
                for classifier_name, pred in predictions.items()
                if pred == style_id
            )
        
        # Get the style with highest weighted votes
        voted_style = max(weighted_votes.items(), key=lambda x: x[1])[0]
        
        # Calculate confidence
        total_weight = sum(CLASSIFICATION_CONFIG['style_weights'].values())
        confidence = weighted_votes[voted_style] / total_weight
        
        result = {
            'learning_style': self.style_mapping[voted_style],
            'confidence': confidence,
            'individual_votes': {
                classifier_name: self.style_mapping[pred]
                for classifier_name, pred in predictions.items()
            },
            'model_metrics': self.model_metrics
        }
        
        if confidence < CLASSIFICATION_CONFIG['confidence_threshold']:
            result['warning'] = 'Low confidence prediction'
            
        return result 