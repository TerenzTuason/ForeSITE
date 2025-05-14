from sklearn.tree import DecisionTreeClassifier
from sklearn.naive_bayes import BernoulliNB
from sklearn.svm import SVC
from sklearn.ensemble import RandomForestClassifier
import numpy as np
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
            0: 'Activist',
            1: 'Reflector',
            2: 'Theorist',
            3: 'Pragmatist'
        }
        
        # Initialize individual classifiers with config parameters
        self.dt_classifier = DecisionTreeClassifier(**MODEL_CONFIG['decision_tree'])
        self.nb_classifier = BernoulliNB(**MODEL_CONFIG['naive_bayes'])
        self.svm_classifier = SVC(**MODEL_CONFIG['svm'])
        self.rf_classifier = RandomForestClassifier(**MODEL_CONFIG['random_forest'])
        
        # Pre-train the models
        self._pretrain_models()
    
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
    
    def _pretrain_models(self):
        # Convert question indices to 0-based indexing
        style_questions = {
            style: [q - 1 for q in questions]
            for style, questions in STYLE_QUESTIONS.items()
        }
        
        def create_pattern(primary_style, secondary_style=None):
            pattern = np.zeros(80)
            # Set primary style questions
            pattern[style_questions[primary_style]] = 1
            
            # Set some secondary style questions if provided
            if secondary_style:
                secondary_indices = np.random.choice(
                    style_questions[secondary_style],
                    size=int(len(style_questions[secondary_style]) * TRAINING_CONFIG['secondary_style_ratio']),
                    replace=False
                )
                pattern[secondary_indices] = 1
            
            # Add random noise
            noise_indices = np.random.choice(
                [i for i in range(80) if i not in style_questions[primary_style]],
                size=int(80 * TRAINING_CONFIG['noise_level']),
                replace=False
            )
            pattern[noise_indices] = 1
            
            # Apply question weights
            return self._apply_question_weights(pattern, primary_style)
        
        # Create training data
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
        
        X_train = np.array(X_train)
        y_train = np.array(y_train)
        
        # Train each classifier
        self.dt_classifier.fit(X_train, y_train)
        self.nb_classifier.fit(X_train, y_train)
        self.svm_classifier.fit(X_train, y_train)
        self.rf_classifier.fit(X_train, y_train)
    
    def predict(self, answers):
        # Convert answers to binary numpy array
        X = np.array(answers).reshape(1, -1)
        
        # Get predictions from each classifier
        predictions = {
            'decision_tree': self.dt_classifier.predict(X)[0],
            'naive_bayes': self.nb_classifier.predict(X)[0],
            'svm': self.svm_classifier.predict(X)[0],
            'random_forest': self.rf_classifier.predict(X)[0]
        }
        
        # Apply weights to votes
        weighted_votes = {}
        for style_id in range(4):  # 4 learning styles
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
            }
        }
        
        # Add warning if confidence is below threshold
        if confidence < CLASSIFICATION_CONFIG['confidence_threshold']:
            result['warning'] = 'Low confidence prediction'
            
        return result 