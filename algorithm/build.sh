#!/bin/bash

# Exit script on any error
set -e

echo "Installing dependencies..."
pip install -r requirements.txt

echo "Training models..."
python train.py

echo "Build script finished." 