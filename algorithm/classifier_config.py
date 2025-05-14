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
        'max_depth': 10,  # Adjust to control tree complexity
        'min_samples_split': 2  # Minimum samples required to split a node
    },
    'naive_bayes': {
        'alpha': 1.0  # Smoothing parameter
    },
    'svm': {
        'kernel': 'rbf',  # Options: 'rbf', 'linear', 'poly'
        'random_state': 42,
        'C': 1.0  # Regularization parameter
    },
    'random_forest': {
        'n_estimators': 100,  # Number of trees
        'random_state': 42,
        'max_depth': 10,  # Maximum depth of trees
        'min_samples_split': 2
    }
}

# Training Data Generation Parameters
TRAINING_CONFIG = {
    'samples_per_style': 50,  # Number of training samples per learning style
    'noise_level': 0.2,  # Proportion of random noise in training data (0.0 to 1.0)
    'secondary_style_ratio': 0.3,  # Proportion of secondary style traits to include
}

# Style Classification Parameters
CLASSIFICATION_CONFIG = {
    'confidence_threshold': 0.6,  # Minimum confidence level for style prediction
    'style_weights': {  # Weights for each classifier in the voting
        'decision_tree': 1.0,
        'naive_bayes': 1.0,
        'svm': 1.0,
        'random_forest': 1.2  # Giving slightly more weight to Random Forest
    }
}

# Style Characteristics Weights
# Higher weights mean these questions have more influence on the final classification
QUESTION_WEIGHTS = {
    'activist': {
        'primary': [10, 23, 34, 48, 72],  # Most important activist questions
        'weight': 1.5  # Multiplier for these questions
    },
    'reflector': {
        'primary': [7, 13, 29, 31, 67],
        'weight': 1.5
    },
    'theorist': {
        'primary': [3, 14, 30, 51, 77],
        'weight': 1.5
    },
    'pragmatist': {
        'primary': [9, 35, 44, 49, 56],
        'weight': 1.5
    }
} 