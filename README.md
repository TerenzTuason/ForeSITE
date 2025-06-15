# Learning Style Prediction API

This project is a Flask-based web API that predicts a user's learning style based on their answers to a questionnaire. It uses a variety of machine learning models to classify learning styles into four categories: Activist, Reflector, Theorist, and Pragmatist.

## Prerequisites

- Python 3.9.0
- Project dependencies for local development and testing.

## Setup for Local Development

1.  **Clone the repository:**
    ```bash
    git clone <repository-url>
    cd <repository-directory>
    ```

2.  **Install dependencies:**
    It is recommended to use a virtual environment.
    ```bash
    python -m venv venv
    source venv/bin/activate  # On Windows use `venv\Scripts\activate`
    ```
    Install the required packages using `requirements-dev.txt`:
    ```bash
    pip install -r requirements-dev.txt
    ```

3.  **Configure for Local Environment:**
    For the application to run correctly in a local development environment, you need to adjust the `learning_style_classifier.py` file.

    -   **Comment out** lines 15 to 20.
    -   **Uncomment** lines 6 to 12.

    This change switches from using the lightweight `tflite-runtime` (for production/deployment) to the full `tensorflow` library, which is needed for training and running the models locally.

    ```python
    # In learning_style_classifier.py

    # Use a try-except block for compatibility between local dev and Heroku >> Uncomment this for local dev
    try:
        # This will work locally if you have tensorflow installed
        import tensorflow as tf
        Interpreter = tf.lite.Interpreter
    except (ImportError, AttributeError):
        # This will work on Heroku with tflite_runtime
        from tflite_runtime.interpreter import Interpreter  # type: ignore

    # Use a try-except block for compatibility between local dev and Heroku >> Uncomment this for heroku
    # try:
    #     # This will work locally if you have tensorflow installed
    #     from tensorflow.lite import Interpreter
    # except ImportError:
    #     # This will work on Heroku with tflite_runtime
    #     from tflite_runtime.interpreter import Interpreter  # type: ignore
    ```

## Training the Models

To train the machine learning models from scratch, run the `train_and_save_models.py` script. This script will generate synthetic data, train several models, evaluate them, and save the trained models and their metrics to the `models/` directory.

```bash
python train_and_save_models.py
```

The training process and model configurations can be tuned in the `classifier_config.py` file.

## Running the Project Locally

To start the Flask API server, run the `app.py` script:

```bash
python app.py
```

The API will be available at `http://localhost:5000`. You can interact with it using a tool like Postman or `curl`.

### API Endpoint

-   **POST /predict**

    Sends a list of 80 answers to get a learning style prediction.

    **Request Body:**
    ```json
    {
        "answers": [0, 1, 0, 1, ..., 0, 1]
    }
    ```

    **Example `curl` command:**
    ```bash
    curl -X POST -H "Content-Type: application/json" -d '{"answers": [0,1,0,1, ...]}' http://localhost:5000/predict
    ```

## Model Configuration

The hyperparameters for all models can be configured in the `classifier_config.py` file. This includes settings for:

-   Decision Tree
-   Random Forest
-   Support Vector Machine (SVM)
-   Logistic Regression
-   XGBoost
-   Convolutional Neural Network (CNN)
-   Blending Ensemble

You can modify `MODEL_CONFIG` in `classifier_config.py` to experiment with different model architectures and parameters.

## Specifying Models for Prediction

You can choose which models are used for the final prediction by modifying the `models_to_use` list in `app.py`.

```python
# In app.py

# Specify the models to use for prediction.
# Options: 'decision_tree', 'random_forest', 'support_vector_machine', 
# 'logistic_regression', 'xgboost', 'cnn', 'blending_ensemble'
models_to_use = ['logistic_regression'] # Example: using only logistic regression

result = classifier.predict(answers, models_to_use=models_to_use)
```

You can include any combination of the available models in this list. The final prediction is a weighted vote based on the `style_weights` defined in `CLASSIFICATION_CONFIG` within `classifier_config.py`.

## How the Prediction Works

The model predicts the learning style through a sophisticated ensemble approach that combines the outputs of multiple machine learning models. Here is a step-by-step explanation of how it works:

### 1. Input: Questionnaire Answers
The process begins when a user provides answers to an 80-question learning style questionnaire. These answers are represented as a list of 80 binary values (0s or 1s).

### 2. The Ensemble of Models
Instead of relying on a single algorithm, the system employs a diverse set of seven different classification models to analyze the answers. This multi-model approach, known as an ensemble, is often more robust and accurate. The models are:
-   **Decision Tree**
-   **Random Forest**
-   **Support Vector Machine (SVM)**
-   **Logistic Regression**
-   **XGBoost**
-   **Convolutional Neural Network (CNN)**
-   **Blending Ensemble**: A "meta-model" that uses the predictions from other models as its input.

### 3. Core Logic: Weighted Voting
The final decision is made through a weighted vote. Each of the seven models is assigned a "weight" in `classifier_config.py`, which represents how much its vote "counts."

1.  Each model makes its own prediction, "voting" for one of the four learning styles: **Activist, Reflector, Theorist, or Pragmatist**.
2.  The system sums the weights of all the models that voted for each style.
3.  The learning style that accumulates the highest total weight is declared the winner.

For example, XGBoost has the highest weight (0.25), making its vote the most influential, while the Decision Tree has the lowest (0.05).

### 4. Confidence Score
The system also calculates a confidence score by dividing the winning style's total weight by the sum of the weights of all models used. This score reflects the level of agreement among the models.

### 5. Model Training
The models are trained on **synthetically generated data**. The `train_and_save_models.py` script creates thousands of example answer sheets by generating "perfect" profiles for each style and then making them more realistic by introducing noise and adding characteristics of a secondary style. 