import os
import json
import joblib
import numpy as np
import tensorflow as tf
from sklearn.model_selection import train_test_split
from sklearn.tree import DecisionTreeClassifier
from sklearn.svm import SVC
from sklearn.ensemble import RandomForestClassifier
from sklearn.linear_model import LogisticRegression
from sklearn.metrics import (
    accuracy_score, f1_score, precision_score, recall_score, roc_auc_score,
    classification_report, confusion_matrix
)
from sklearn.preprocessing import LabelBinarizer
import xgboost as xgb
import time
import matplotlib.pyplot as plt
import seaborn as sns

from classifier_config import (
    STYLE_QUESTIONS,
    MODEL_CONFIG,
    TRAINING_CONFIG,
    QUESTION_WEIGHTS
)


def _generate_training_data():
    """Generates synthetic training data for the learning style models."""
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

        return pattern

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


def _build_cnn_model():
    """Builds and compiles the CNN model."""
    model = tf.keras.Sequential()
    model.add(tf.keras.layers.Reshape((80, 1), input_shape=(80,)))
    for conv_layer in MODEL_CONFIG['cnn']['conv_layers']:
        model.add(tf.keras.layers.Conv1D(
            filters=conv_layer['filters'],
            kernel_size=conv_layer['kernel_size'],
            activation='relu'
        ))
        model.add(tf.keras.layers.MaxPooling1D(2))
    model.add(tf.keras.layers.Flatten())
    for units in MODEL_CONFIG['cnn']['dense_layers']:
        model.add(tf.keras.layers.Dense(units, activation='relu'))
        model.add(tf.keras.layers.Dropout(0.2))
    model.add(tf.keras.layers.Dense(4, activation='softmax'))

    model.compile(
        optimizer=tf.keras.optimizers.Adam(learning_rate=MODEL_CONFIG['cnn']['learning_rate']),
        loss='sparse_categorical_crossentropy',
        metrics=['accuracy']
    )
    return model


def _calculate_metrics(y_true, y_pred, y_pred_proba, training_time):
    """Calculates a dictionary of performance metrics."""
    lb = LabelBinarizer()
    y_true_bin = lb.fit_transform(y_true)
    
    # Handle cases where a class might not be present in a batch
    if y_true_bin.shape[1] == 1:
        y_true_bin = lb.fit_transform(np.concatenate((y_true, np.array([0,1,2,3]))))[:len(y_true)]

    # Calculate specificity
    specificity_scores = []
    for i in range(y_true_bin.shape[1]):
        tn = np.sum((y_true_bin[:, i] == 0) & (np.argmax(y_pred_proba, axis=1) != i))
        fp = np.sum((y_true_bin[:, i] == 0) & (np.argmax(y_pred_proba, axis=1) == i))
        specificity = tn / (tn + fp) if (tn + fp) > 0 else 1.0
        specificity_scores.append(specificity)

    return {
        'accuracy': accuracy_score(y_true, y_pred),
        'f1_score': f1_score(y_true, y_pred, average='weighted'),
        'precision': precision_score(y_true, y_pred, average='weighted', zero_division=0),
        'recall': recall_score(y_true, y_pred, average='weighted', zero_division=0),
        'specificity': np.mean(specificity_scores),
        'auc_roc': roc_auc_score(y_true_bin, y_pred_proba, average='weighted', multi_class='ovr'),
        'training_time': training_time
    }


def train_and_save_models():
    """Trains all models, calculates metrics, and saves them to the 'models/' directory."""
    print("Generating training data...")
    X_data, y_data = _generate_training_data()
    X_train, X_val, y_train, y_val = train_test_split(
        X_data, y_data, test_size=0.2, random_state=42, stratify=y_data
    )

    if not os.path.exists('models'):
        os.makedirs('models')
    if not os.path.exists('evaluation_results'):
        os.makedirs('evaluation_results')

    model_metrics = {}

    # --- Train and Evaluate Scikit-learn, XGBoost models ---
    classifiers = {
        'decision_tree': DecisionTreeClassifier(**MODEL_CONFIG['decision_tree']),
        'random_forest': RandomForestClassifier(**MODEL_CONFIG['random_forest']),
        'support_vector_machine': SVC(**MODEL_CONFIG['support_vector_machine'], probability=True),
        'logistic_regression': LogisticRegression(**MODEL_CONFIG['logistic_regression']),
        'xgboost': xgb.XGBClassifier(**MODEL_CONFIG['xgboost']),
    }

    print("Training and evaluating base models...")
    for name, clf in classifiers.items():
        print(f"  Training {name}...")
        start_time = time.time()
        clf.fit(X_train, y_train)
        training_time = time.time() - start_time
        
        y_pred = clf.predict(X_val)
        y_pred_proba = clf.predict_proba(X_val)
        model_metrics[name] = _calculate_metrics(y_val, y_pred, y_pred_proba, training_time)
        _save_evaluation_results(y_val, y_pred, name)
        _save_confidence_curves(y_val, y_pred_proba, name)
        
        # Save the model
        if name == 'xgboost':
            clf.save_model(f'models/{name}.json')
        else:
            joblib.dump(clf, f'models/{name}.joblib')
            
    print("Base models trained and saved.")

    # --- Train and Evaluate CNN Model ---
    print("Training and evaluating CNN model...")
    cnn_model = _build_cnn_model()
    
    start_time = time.time()
    cnn_model.fit(
        X_train, y_train,
        epochs=MODEL_CONFIG['cnn']['epochs'],
        batch_size=MODEL_CONFIG['cnn']['batch_size'],
        validation_data=(X_val, y_val),
        verbose=0
    )
    training_time = time.time() - start_time
    
    y_pred_proba_cnn = cnn_model.predict(X_val)
    y_pred_cnn = np.argmax(y_pred_proba_cnn, axis=1)
    model_metrics['cnn'] = _calculate_metrics(y_val, y_pred_cnn, y_pred_proba_cnn, training_time)
    _save_evaluation_results(y_val, y_pred_cnn, 'cnn')
    _save_confidence_curves(y_val, y_pred_proba_cnn, 'cnn')
    
    # Convert to TFLite and save
    converter = tf.lite.TFLiteConverter.from_keras_model(cnn_model)
    tflite_model = converter.convert()
    with open('models/cnn_model.tflite', 'wb') as f:
        f.write(tflite_model)
    print("CNN model trained and saved as cnn_model.tflite.")

    # --- Train and Evaluate Blending Ensemble Meta-Learner ---
    print("Training and evaluating blending ensemble meta-learner...")
    base_predictions_train = np.column_stack([
        classifiers[model_name].predict_proba(X_train)
        for model_name in MODEL_CONFIG['blending_ensemble']['base_models']
    ])
    
    meta_learner = LogisticRegression(**MODEL_CONFIG['blending_ensemble']['meta_learner_params'])
    start_time = time.time()
    meta_learner.fit(base_predictions_train, y_train)
    training_time = time.time() - start_time
    
    base_predictions_val = np.column_stack([
        classifiers[model_name].predict_proba(X_val)
        for model_name in MODEL_CONFIG['blending_ensemble']['base_models']
    ])
    y_pred_meta = meta_learner.predict(base_predictions_val)
    y_pred_proba_meta = meta_learner.predict_proba(base_predictions_val)
    model_metrics['blending_ensemble'] = _calculate_metrics(y_val, y_pred_meta, y_pred_proba_meta, training_time)
    _save_evaluation_results(y_val, y_pred_meta, 'blending_ensemble')
    _save_confidence_curves(y_val, y_pred_proba_meta, 'blending_ensemble')
    
    joblib.dump(meta_learner, 'models/blending_meta_learner.joblib')
    print("Blending meta-learner trained and saved.")

    # --- Save Metrics ---
    with open('models/model_metrics.json', 'w') as f:
        json.dump(model_metrics, f, indent=2)
    print("Model metrics saved to models/model_metrics.json")
    
    print("\nAll models and metrics have been trained and saved in the 'models/' directory.")


def _save_evaluation_results(y_true, y_pred, model_name):
    """Saves the classification report and confusion matrix."""
    report = classification_report(y_true, y_pred)
    with open(f'evaluation_results/{model_name}_classification_report.txt', 'w') as f:
        f.write(report)
    print(f"Classification report for {model_name} saved.")

    cm = confusion_matrix(y_true, y_pred)
    plt.figure(figsize=(10, 7))
    sns.heatmap(cm, annot=True, fmt='d', cmap='Blues')
    plt.title(f'Confusion Matrix for {model_name}')
    plt.ylabel('Actual')
    plt.xlabel('Predicted')
    plt.savefig(f'evaluation_results/{model_name}_confusion_matrix.png')
    plt.close()
    print(f"Confusion matrix for {model_name} saved.")


def _save_confidence_curves(y_true, y_pred_proba, model_name):
    """Calculates and saves precision-confidence, recall-confidence, and f1-confidence curves."""
    thresholds = np.linspace(0.01, 1.0, 100)
    precisions = []
    recalls = []
    f1s = []

    for t in thresholds:
        confidences = np.max(y_pred_proba, axis=1)
        y_pred = np.argmax(y_pred_proba, axis=1)
        
        mask = confidences >= t
        filtered_y_true = y_true[mask]
        filtered_y_pred = y_pred[mask]
        
        if len(filtered_y_true) == 0:
            precisions.append(0)
            recalls.append(0)
            f1s.append(0)
        else:
            precisions.append(precision_score(filtered_y_true, filtered_y_pred, average='weighted', zero_division=0))
            recalls.append(recall_score(filtered_y_true, filtered_y_pred, average='weighted', zero_division=0))
            f1s.append(f1_score(filtered_y_true, filtered_y_pred, average='weighted', zero_division=0))

    # Plot Precision-Confidence Curve
    plt.figure(figsize=(8, 6))
    plt.plot(thresholds, precisions, label='Precision')
    plt.title(f'Precision-Confidence Curve for {model_name}')
    plt.xlabel('Confidence')
    plt.ylabel('Precision')
    plt.grid(True)
    plt.legend()
    plt.savefig(f'evaluation_results/{model_name}_precision_confidence_curve.png')
    plt.close()

    # Plot Recall-Confidence Curve
    plt.figure(figsize=(8, 6))
    plt.plot(thresholds, recalls, label='Recall')
    plt.title(f'Recall-Confidence Curve for {model_name}')
    plt.xlabel('Confidence')
    plt.ylabel('Recall')
    plt.grid(True)
    plt.legend()
    plt.savefig(f'evaluation_results/{model_name}_recall_confidence_curve.png')
    plt.close()

    # Plot F1-Confidence Curve
    plt.figure(figsize=(8, 6))
    plt.plot(thresholds, f1s, label='F1-Score')
    plt.title(f'F1-Confidence Curve for {model_name}')
    plt.xlabel('Confidence')
    plt.ylabel('F1')
    plt.grid(True)
    plt.legend()
    plt.savefig(f'evaluation_results/{model_name}_f1_confidence_curve.png')
    plt.close()
    
    print(f"Confidence curves for {model_name} saved.")


if __name__ == '__main__':
    train_and_save_models() 