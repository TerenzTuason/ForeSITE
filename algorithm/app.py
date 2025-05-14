from flask import Flask, request, jsonify
from learning_style_classifier import LearningStyleClassifier
from flask_cors import CORS

app = Flask(__name__)
CORS(app)

# Initialize the classifier
classifier = LearningStyleClassifier()

@app.route('/predict', methods=['POST'])
def predict_learning_style():
    try:
        data = request.get_json()
        
        if not data or 'answers' not in data:
            return jsonify({'error': 'No answers provided'}), 400
            
        answers = data['answers']
        
        if not isinstance(answers, list) or len(answers) != 80:
            return jsonify({'error': 'Answers must be a list of 80 binary values (0 or 1)'}), 400
            
        if not all(isinstance(x, int) and x in [0, 1] for x in answers):
            return jsonify({'error': 'All answers must be binary (0 or 1)'}), 400
            
        result = classifier.predict(answers)
        return jsonify(result)
        
    except Exception as e:
        return jsonify({'error': str(e)}), 500

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000, debug=True) 