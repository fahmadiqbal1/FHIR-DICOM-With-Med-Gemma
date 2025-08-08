"""Main application entry point"""

import os
import sys
import logging
import yaml
from pathlib import Path
from fastapi import FastAPI, HTTPException
from fastapi.middleware.cors import CORSMiddleware
from contextlib import asynccontextmanager

# Add src to Python path
sys.path.insert(0, os.path.join(os.path.dirname(__file__)))

from database import init_database, check_database, db_manager
from fhir.api import fhir_app

# Configure logging
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

@asynccontextmanager
async def lifespan(app: FastAPI):
    """Application lifespan management"""
    # Startup
    logger.info("Starting FHIR-DICOM-Med-Gemma application...")
    
    try:
        # Initialize database
        init_database()
        logger.info("Database initialized")
        
        # Check database connection
        if not check_database():
            logger.error("Database connection failed")
            raise RuntimeError("Database connection failed")
        
        logger.info("Application startup completed")
        yield
        
    except Exception as e:
        logger.error(f"Application startup failed: {str(e)}")
        raise
    
    # Shutdown
    logger.info("Shutting down application...")

# Create main FastAPI application
app = FastAPI(
    title="FHIR-DICOM-Med-Gemma System",
    description="FHIR-DICOM integration with Med-Gemma AI model for medical image analysis",
    version="1.0.0",
    lifespan=lifespan
)

# Configure CORS
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Configure appropriately for production
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

# Mount FHIR API
app.mount("/fhir", fhir_app)

@app.get("/")
async def root():
    """Root endpoint"""
    return {
        "message": "FHIR-DICOM-Med-Gemma System",
        "version": "1.0.0",
        "status": "running",
        "fhir_endpoint": "/fhir",
        "health_check": "/health"
    }

@app.get("/health")
async def health_check():
    """Comprehensive health check endpoint"""
    try:
        health_status = {
            "status": "healthy",
            "timestamp": "2025-01-08T15:07:00Z",
            "version": "1.0.0",
            "services": {}
        }
        
        # Check database
        db_healthy = check_database()
        health_status["services"]["database"] = {
            "status": "healthy" if db_healthy else "unhealthy",
            "details": db_manager.get_database_info()
        }
        
        # Check file system
        try:
            storage_path = Path("./data")
            storage_path.mkdir(exist_ok=True)
            health_status["services"]["storage"] = {
                "status": "healthy",
                "path": str(storage_path.absolute()),
                "writable": os.access(storage_path, os.W_OK)
            }
        except Exception as e:
            health_status["services"]["storage"] = {
                "status": "unhealthy",
                "error": str(e)
            }
        
        # Overall status
        all_healthy = all(
            service["status"] == "healthy" 
            for service in health_status["services"].values()
        )
        health_status["status"] = "healthy" if all_healthy else "degraded"
        
        return health_status
        
    except Exception as e:
        logger.error(f"Health check failed: {str(e)}")
        raise HTTPException(status_code=503, detail="Service unavailable")

@app.get("/config")
async def get_configuration():
    """Get application configuration (non-sensitive)"""
    try:
        config = {
            "environment": os.getenv("ENVIRONMENT", "development"),
            "debug": os.getenv("DEBUG", "false").lower() == "true",
            "fhir": {
                "server_base": os.getenv("FHIR_SERVER_BASE", "http://localhost:8000/fhir"),
                "validation_enabled": True
            },
            "dicom": {
                "storage_path": os.getenv("DICOM_STORAGE_PATH", "./data/dicom"),
                "max_file_size": 104857600  # 100MB
            },
            "medgemma": {
                "model_name": os.getenv("MEDGEMMA_MODEL", "google/medgemma-7b"),
                "device": os.getenv("DEVICE", "auto")
            }
        }
        
        return config
        
    except Exception as e:
        logger.error(f"Error getting configuration: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@app.get("/status")
async def get_system_status():
    """Get detailed system status"""
    try:
        status = {
            "application": {
                "name": "FHIR-DICOM-Med-Gemma",
                "version": "1.0.0",
                "uptime": "N/A",  # Would implement uptime tracking
                "environment": os.getenv("ENVIRONMENT", "development")
            },
            "database": {
                "connected": check_database(),
                "info": db_manager.get_database_info()
            },
            "storage": {
                "base_path": os.getenv("DICOM_STORAGE_PATH", "./data/dicom"),
                "available_space": "N/A"  # Would implement disk space check
            },
            "features": {
                "fhir_server": True,
                "dicom_processing": True,
                "medgemma_integration": False,  # Not yet implemented
                "ai_analysis": False  # Not yet implemented
            }
        }
        
        return status
        
    except Exception as e:
        logger.error(f"Error getting system status: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

def main():
    """Main application entry point"""
    import uvicorn
    
    # Load configuration
    port = int(os.getenv("PORT", 8000))
    host = os.getenv("HOST", "0.0.0.0")
    
    logger.info(f"Starting server on {host}:{port}")
    
    uvicorn.run(
        "main:app",
        host=host,
        port=port,
        reload=os.getenv("DEBUG", "false").lower() == "true",
        log_level="info"
    )

if __name__ == "__main__":
    main()