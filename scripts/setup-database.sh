#!/bin/bash

# Database initialization and migration script

set -e

echo "Starting database setup..."

# Load environment variables
if [ -f .env ]; then
    export $(cat .env | xargs)
fi

# Set default values
DATABASE_URL=${DATABASE_URL:-"postgresql://postgres:postgres@localhost:5432/fhir_dicom_dev"}
ENVIRONMENT=${ENVIRONMENT:-"development"}

echo "Environment: $ENVIRONMENT"
echo "Database URL: ${DATABASE_URL%:*}:***@${DATABASE_URL#*@}"

# Install Python dependencies if needed
if [ ! -d "venv" ]; then
    echo "Creating virtual environment..."
    python3 -m venv venv
fi

source venv/bin/activate
pip install -r requirements.txt

# Run database migrations
echo "Running database migrations..."
export PYTHONPATH="${PYTHONPATH}:$(pwd)/src"

# Initialize Alembic if needed
if [ ! -f "alembic.ini" ]; then
    echo "Initializing Alembic..."
    alembic init migrations
fi

# Create initial migration if none exist
if [ ! "$(ls -A migrations/versions 2>/dev/null)" ]; then
    echo "Creating initial migration..."
    alembic revision --autogenerate -m "Initial migration"
fi

# Run migrations
echo "Applying migrations..."
alembic upgrade head

echo "Database setup completed successfully!"

# Optional: Run database validation
if [ "$1" = "--validate" ]; then
    echo "Running database validation..."
    python3 -c "
import sys
sys.path.insert(0, 'src')
from database import check_database
if check_database():
    print('✓ Database connection successful')
else:
    print('✗ Database connection failed')
    sys.exit(1)
"
fi

echo "Setup script completed!"