# Configuration for Learning Style Classifier

# Learning Style Question Mappings (1-based indexing as per questionnaire)
STYLE_QUESTIONS = {
    'activist': [2, 4, 6, 10, 17, 23, 24, 32, 34, 38, 40, 43, 45, 48, 58, 64, 71, 72, 74, 79],
    'reflector': [7, 13, 15, 16, 25, 28, 29, 31, 33, 36, 39, 41, 46, 52, 55, 60, 62, 66, 67, 76],
    'theorist': [1, 3, 8, 12, 14, 18, 20, 22, 26, 30, 42, 47, 51, 57, 61, 63, 68, 75, 77, 78],
    'pragmatist': [5, 9, 11, 19, 21, 27, 35, 37, 44, 49, 50, 53, 54, 56, 59, 65, 69, 70, 73, 80]
}

# Model Hyperparameters
MODEL_CONFIG = {
    'decision_tree': {
        'random_state': 42,
        'max_depth': 8,
        'min_samples_split': 5,
        'min_samples_leaf': 2
    },
    'random_forest': {
        'n_estimators': 400,
        'random_state': 42,
        'max_depth': 12,
        'min_samples_split': 10,
        'min_samples_leaf': 4
    },
    'support_vector_machine': {
        'kernel': 'rbf',
        'gamma': 'scale',
        'C': 1.0,
        'random_state': 42
    },
    'logistic_regression': {
        'random_state': 42,
        'max_iter': 2000,
        'multi_class': 'multinomial',
        'solver': 'lbfgs'
    },
    'cnn': {
        'epochs': 60,
        'batch_size': 32,
        'learning_rate': 0.001,
        'conv_layers': [
            {'filters': 32, 'kernel_size': 3, 'batch_norm': True},
            {'filters': 64, 'kernel_size': 3, 'batch_norm': True}
        ],
        'dense_layers': [128],
        'dropout_rate': 0.5
    },
    'xgboost': {
        'n_estimators': 300,
        'learning_rate': 0.05,
        'max_depth': 6,
        'subsample': 0.8,
        'colsample_bytree': 0.8,
        'random_state': 42
    },
    'blending_ensemble': {
        'meta_learner': 'logistic_regression',
        'base_models': ['decision_tree', 'random_forest', 'xgboost'],
        'meta_learner_params': {
            'random_state': 42,
            'max_iter': 2000
        }
    }
}

# Training Data Generation Parameters
TRAINING_CONFIG = {
    'samples_per_style': 2000,
    'noise_level': 0.05,
    'secondary_style_ratio': 0.4,
}

# Style Classification Parameters
CLASSIFICATION_CONFIG = {
    'confidence_threshold': 0.4,
    'style_weights': {
        'decision_tree': 0.05,
        'random_forest': 0.15,
        'support_vector_machine': 0.10,
        'logistic_regression': 0.10,
        'cnn': 0.15,
        'xgboost': 0.25,
        'blending_ensemble': 0.20
    }
}

# Style Characteristics Weights
# Higher weights mean these questions have more influence on the final classification
QUESTION_WEIGHTS = {
    'activist': {
        'primary': [2, 10, 23, 34, 72],  # Most important activist questions
        'weight': 3.0
    },
    'reflector': {
        'primary': [7, 15, 29, 31, 67],
        'weight': 1.5
    },
    'theorist': {
        'primary': [3, 14, 51, 75, 77],
        'weight': 4.0
    },
    'pragmatist': {
        'primary': [9, 35, 44, 49, 56],
        'weight': 4.0
    }
} 