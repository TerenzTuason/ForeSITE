from learning_style_classifier import LearningStyleClassifier

if __name__ == '__main__':
    # This will train the models and save them to the 'models' directory.
    # We instantiate the classifier in training mode, which prevents it from
    # trying to load non-existent model files.
    print("Starting model training...")
    classifier_for_training = LearningStyleClassifier(train_mode=True)
    classifier_for_training.train_and_save_models(models_dir='models')
    print("Model training complete. Models saved in 'models' directory.") 