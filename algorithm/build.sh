#!/bin/bash

# Exit script on any error
set -e

echo "Installing build dependencies..."
pip install -r requirements-build.txt

echo "Training models..."
python train.py

echo "Build script finished." 