# Configuration for Learning Style Classifier

# Learning Style Question Mappings (1-based indexing as per questionnaire)
STYLE_QUESTIONS = {
    'activist': [2, 4, 6, 10, 17, 23, 24, 32, 34, 38, 40, 43, 45, 48, 58, 64, 71, 72, 74, 79],
    'reflector': [7, 13, 15, 16, 25, 28, 29, 31, 33, 36, 39, 41, 46, 52, 55, 60, 62, 66, 67, 76],
    'theorist': [1, 3, 8, 12, 14, 18, 20, 22, 26, 30, 42, 47, 51, 57, 61, 63, 68, 75, 77, 78],
    'pragmatist': [5, 9, 11, 19, 21, 27, 35, 37, 44, 49, 50, 53, 54, 56, 59, 65, 69, 70, 73, 80]
}

# Model Parameters
MODEL_CONFIG = {
    'decision_tree': {
        'random_state': 42,
        'max_depth': 10,
        'min_samples_split': 2
    },
    'random_forest': {
        'n_estimators': 100,
        'random_state': 42,
        'max_depth': 10,
        'min_samples_split': 2
    },
    'support_vector_machine': {
        'kernel': 'rbf',
        'random_state': 42,
        'C': 1.0
    },
    'logistic_regression': {
        'random_state': 42,
        'max_iter': 1000,
        'multi_class': 'multinomial',
        'solver': 'lbfgs'
    },
    'cnn': {
        'epochs': 50,
        'batch_size': 32,
        'learning_rate': 0.001,
        'conv_layers': [
            {'filters': 32, 'kernel_size': 3},
            {'filters': 64, 'kernel_size': 3}
        ],
        'dense_layers': [128, 64]
    },
    'xgboost': {
        'n_estimators': 100,
        'learning_rate': 0.1,
        'max_depth': 6,
        'random_state': 42
    },
    'blending_ensemble': {
        'meta_learner': 'logistic_regression',
        'base_models': ['decision_tree', 'random_forest', 'support_vector_machine', 'xgboost'],
        'meta_learner_params': {
            'random_state': 42,
            'max_iter': 1000
        }
    }
}

# Training Data Generation Parameters
TRAINING_CONFIG = {
    'samples_per_style': 200,  # Number of training samples per learning style
    'noise_level': 0.1,  # Proportion of random noise in training data (0.0 to 1.0)
    'secondary_style_ratio': 0.4,  # Proportion of secondary style traits to include
}

# Style Classification Parameters
CLASSIFICATION_CONFIG = {
    'confidence_threshold': 0.5,  # Minimum confidence level for style prediction
    'style_weights': {  # Weights for each classifier in the voting
        'decision_tree': 1.0,
        'random_forest': 1.3,
        'support_vector_machine': 1.0,
        'logistic_regression': 1.0,
        'cnn': 1.3,
        'xgboost': 1.3,
        'blending_ensemble': 1.5
    }
}

# Style Characteristics Weights
# Higher weights mean these questions have more influence on the final classification
QUESTION_WEIGHTS = {
    'activist': {
        'primary': [2, 10, 23, 34, 72],  # Most important activist questions
        'weight': 2.0  # Multiplier for these questions
    },
    'reflector': {
        'primary': [7, 15, 29, 31, 67],
        'weight': 2.0
    },
    'theorist': {
        'primary': [3, 14, 51, 75, 77],
        'weight': 2.0
    },
    'pragmatist': {
        'primary': [9, 35, 44, 49, 56],
        'weight': 2.0
    }
} 