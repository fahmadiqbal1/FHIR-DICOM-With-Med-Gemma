"""FHIR API endpoints using FastAPI"""

from fastapi import FastAPI, HTTPException, Depends, Query, Request
from fastapi.responses import JSONResponse
from sqlalchemy.orm import Session
from typing import Optional, Dict, Any, List
import logging
from datetime import datetime
from .server import FHIRServer
from .validator import FHIRValidator
from ..database import get_db_session

logger = logging.getLogger(__name__)

# Initialize FastAPI app for FHIR endpoints
fhir_app = FastAPI(
    title="FHIR API",
    description="FHIR-compliant API for DICOM-Med-Gemma integration",
    version="1.0.0"
)

def get_fhir_server(db: Session = Depends(get_db_session)) -> FHIRServer:
    """Dependency to get FHIR server instance"""
    return FHIRServer(db)

@fhir_app.get("/metadata")
async def get_capability_statement(fhir_server: FHIRServer = Depends(get_fhir_server)):
    """Get FHIR CapabilityStatement"""
    try:
        return fhir_server.get_capability_statement()
    except Exception as e:
        logger.error(f"Error getting capability statement: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

# Patient endpoints
@fhir_app.post("/Patient")
async def create_patient(
    patient_data: Dict[str, Any],
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Create a new FHIR Patient resource"""
    try:
        result = fhir_server.create_patient(patient_data)
        return JSONResponse(content=result, status_code=201)
    except ValueError as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        logger.error(f"Error creating patient: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@fhir_app.get("/Patient/{patient_id}")
async def get_patient(
    patient_id: str,
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Get a FHIR Patient resource by ID"""
    try:
        result = fhir_server.get_patient(patient_id)
        if not result:
            raise HTTPException(status_code=404, detail=f"Patient {patient_id} not found")
        return result
    except HTTPException:
        raise
    except Exception as e:
        logger.error(f"Error getting patient {patient_id}: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@fhir_app.put("/Patient/{patient_id}")
async def update_patient(
    patient_id: str,
    patient_data: Dict[str, Any],
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Update a FHIR Patient resource"""
    try:
        result = fhir_server.update_patient(patient_id, patient_data)
        return result
    except ValueError as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        logger.error(f"Error updating patient {patient_id}: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@fhir_app.delete("/Patient/{patient_id}")
async def delete_patient(
    patient_id: str,
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Delete a FHIR Patient resource"""
    try:
        result = fhir_server.delete_patient(patient_id)
        return JSONResponse(content={"deleted": result}, status_code=204)
    except ValueError as e:
        raise HTTPException(status_code=404, detail=str(e))
    except Exception as e:
        logger.error(f"Error deleting patient {patient_id}: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@fhir_app.get("/Patient")
async def search_patients(
    family: Optional[str] = Query(None, description="Family name"),
    given: Optional[str] = Query(None, description="Given name"),
    gender: Optional[str] = Query(None, description="Gender"),
    birthdate: Optional[str] = Query(None, description="Birth date (YYYY-MM-DD)"),
    identifier: Optional[str] = Query(None, description="Patient identifier"),
    _count: Optional[int] = Query(20, description="Number of results to return"),
    _offset: Optional[int] = Query(0, description="Offset for pagination"),
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Search for FHIR Patient resources"""
    try:
        search_params = {}
        if family:
            search_params['family'] = family
        if given:
            search_params['given'] = given
        if gender:
            search_params['gender'] = gender
        if birthdate:
            search_params['birthdate'] = birthdate
        if identifier:
            search_params['identifier'] = identifier
        
        search_params['_count'] = _count
        search_params['_offset'] = _offset
        
        result = fhir_server.search_patients(search_params)
        return result
    except Exception as e:
        logger.error(f"Error searching patients: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

# ImagingStudy endpoints
@fhir_app.post("/ImagingStudy")
async def create_imaging_study(
    imaging_study_data: Dict[str, Any],
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Create a new FHIR ImagingStudy resource"""
    try:
        result = fhir_server.create_imaging_study(imaging_study_data)
        return JSONResponse(content=result, status_code=201)
    except ValueError as e:
        raise HTTPException(status_code=400, detail=str(e))
    except Exception as e:
        logger.error(f"Error creating imaging study: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@fhir_app.get("/ImagingStudy/{study_id}")
async def get_imaging_study(
    study_id: str,
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Get a FHIR ImagingStudy resource by ID"""
    try:
        # Implementation would be similar to patient get
        # This is a placeholder for the actual implementation
        raise HTTPException(status_code=501, detail="Not implemented yet")
    except Exception as e:
        logger.error(f"Error getting imaging study {study_id}: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

# DiagnosticReport endpoints
@fhir_app.post("/DiagnosticReport")
async def create_diagnostic_report(
    diagnostic_report_data: Dict[str, Any],
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Create a new FHIR DiagnosticReport resource"""
    try:
        # Implementation would be similar to patient create
        # This is a placeholder for the actual implementation
        raise HTTPException(status_code=501, detail="Not implemented yet")
    except Exception as e:
        logger.error(f"Error creating diagnostic report: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

@fhir_app.get("/DiagnosticReport/{report_id}")
async def get_diagnostic_report(
    report_id: str,
    fhir_server: FHIRServer = Depends(get_fhir_server)
):
    """Get a FHIR DiagnosticReport resource by ID"""
    try:
        # Implementation would be similar to patient get
        # This is a placeholder for the actual implementation
        raise HTTPException(status_code=501, detail="Not implemented yet")
    except Exception as e:
        logger.error(f"Error getting diagnostic report {report_id}: {str(e)}")
        raise HTTPException(status_code=500, detail="Internal server error")

# Health check endpoint
@fhir_app.get("/health")
async def health_check():
    """Health check endpoint"""
    return {
        "status": "healthy",
        "timestamp": datetime.utcnow().isoformat(),
        "service": "FHIR API",
        "version": "1.0.0"
    }

# Error handlers
@fhir_app.exception_handler(ValidationError)
async def validation_exception_handler(request: Request, exc: ValidationError):
    """Handle validation errors"""
    return JSONResponse(
        status_code=400,
        content={
            "resourceType": "OperationOutcome",
            "issue": [{
                "severity": "error",
                "code": "invalid",
                "details": {"text": str(exc)}
            }]
        }
    )

@fhir_app.exception_handler(Exception)
async def general_exception_handler(request: Request, exc: Exception):
    """Handle general exceptions"""
    logger.error(f"Unhandled exception: {str(exc)}")
    return JSONResponse(
        status_code=500,
        content={
            "resourceType": "OperationOutcome",
            "issue": [{
                "severity": "error",
                "code": "exception",
                "details": {"text": "Internal server error"}
            }]
        }
    )