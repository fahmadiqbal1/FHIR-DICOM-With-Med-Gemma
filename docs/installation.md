# Installation Guide

## Prerequisites

- Python 3.8+ 
- PostgreSQL 12+
- Redis (optional, for caching)
- Docker and Docker Compose (for containerized deployment)

## Local Development Setup

### 1. Clone the Repository

```bash
git clone https://github.com/fahmadiqbal1/FHIR-DICOM-With-Med-Gemma.git
cd FHIR-DICOM-With-Med-Gemma
```

### 2. Set Up Python Environment

```bash
# Create virtual environment
python3 -m venv venv
source venv/bin/activate  # On Windows: venv\Scripts\activate

# Install dependencies
pip install -r requirements.txt
```

### 3. Configure Environment

```bash
# Copy environment template
cp .env.example .env

# Edit .env file with your configuration
nano .env
```

### 4. Set Up Database

```bash
# Start PostgreSQL (if not already running)
# On Ubuntu/Debian:
sudo systemctl start postgresql

# Create database
sudo -u postgres createdb fhir_dicom_dev

# Run database setup script
./scripts/setup-database.sh --validate
```

### 5. Initialize Database Schema

```bash
# Run migrations
alembic upgrade head
```

### 6. Start the Application

```bash
# Set Python path
export PYTHONPATH="${PYTHONPATH}:$(pwd)/src"

# Start the server
python src/main.py
```

The application will be available at `http://localhost:8000`

## Docker Deployment

### Quick Start with Docker Compose

```bash
# Build and start all services
docker-compose up -d

# Check service status
docker-compose ps

# View logs
docker-compose logs -f app
```

### Environment Variables

Key environment variables to configure:

```bash
# Database
DATABASE_URL=postgresql://user:password@host:port/database
DB_HOST=localhost
DB_PORT=5432
DB_NAME=fhir_dicom_db
DB_USER=postgres
DB_PASSWORD=postgres

# Application
ENVIRONMENT=production
PORT=8000
HOST=0.0.0.0
DEBUG=false

# FHIR Configuration
FHIR_SERVER_BASE=http://localhost:8000/fhir

# DICOM Storage
DICOM_STORAGE_PATH=./data/dicom

# Med-Gemma Model
MEDGEMMA_MODEL=google/medgemma-7b
MEDGEMMA_MODEL_PATH=./models/medgemma
HUGGINGFACE_TOKEN=your-token-here

# Security
JWT_SECRET=your-super-secret-key
API_KEY=your-api-key
```

## Production Deployment

### 1. Security Configuration

- Set strong passwords and secrets
- Configure SSL/TLS certificates
- Set up firewall rules
- Enable database encryption

### 2. Database Setup

```bash
# Create production database
createdb -h your-db-host -U postgres fhir_dicom_prod

# Run migrations
ENVIRONMENT=production alembic upgrade head
```

### 3. Application Deployment

```bash
# Build production Docker image
docker build -t fhir-dicom-medgemma:latest .

# Deploy with production compose file
docker-compose -f docker-compose.prod.yml up -d
```

### 4. Monitoring and Logging

- Configure log aggregation
- Set up health monitoring
- Enable performance metrics
- Configure backup schedules

## Health Checks

After installation, verify the system is working:

```bash
# Check application health
curl http://localhost:8000/health

# Check FHIR capability statement
curl http://localhost:8000/fhir/metadata

# Check database connection
python -c "
import sys
sys.path.insert(0, 'src')
from database import check_database
print('Database OK' if check_database() else 'Database Error')
"
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   ```bash
   # Check PostgreSQL is running
   sudo systemctl status postgresql
   
   # Check database exists
   psql -h localhost -U postgres -l
   ```

2. **Permission Errors**
   ```bash
   # Fix file permissions
   chmod +x scripts/*.sh
   chown -R app:app data/ logs/
   ```

3. **Port Already in Use**
   ```bash
   # Check what's using port 8000
   lsof -i :8000
   
   # Change port in .env file
   PORT=8001
   ```

4. **Model Loading Issues**
   ```bash
   # Check HuggingFace token
   huggingface-cli login
   
   # Verify model access
   python -c "from transformers import AutoTokenizer; print('Model accessible')"
   ```

### Getting Help

- Check the logs: `docker-compose logs app`
- Review configuration: `curl http://localhost:8000/config`
- Check system status: `curl http://localhost:8000/status`

## Next Steps

After installation:

1. Configure FHIR resources
2. Set up DICOM storage
3. Test Med-Gemma integration
4. Configure monitoring and backups
5. Set up user authentication