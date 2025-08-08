"""FHIR Resource management utilities"""

from fhir.resources.patient import Patient as FHIRPatient
from fhir.resources.imagingstudy import ImagingStudy as FHIRImagingStudy
from fhir.resources.diagnosticreport import DiagnosticReport as FHIRDiagnosticReport
from fhir.resources.observation import Observation as FHIRObservation
from fhir.resources.documentreference import DocumentReference as FHIRDocumentReference
from sqlalchemy.orm import sessionmaker
import logging
import json
import uuid
from datetime import datetime
from ..models import Patient, ImagingStudy, DiagnosticReport

logger = logging.getLogger(__name__)

class ResourceManager:
    """Manages FHIR resources and their database representations"""
    
    def __init__(self, database_session):
        self.db_session = database_session
    
    def fhir_to_db_patient(self, fhir_patient: FHIRPatient):
        """Convert FHIR Patient to database Patient"""
        patient_data = {
            'fhir_id': fhir_patient.id or str(uuid.uuid4()),
            'fhir_resource': fhir_patient.dict()
        }
        
        # Extract commonly queried fields
        if fhir_patient.name and len(fhir_patient.name) > 0:
            name = fhir_patient.name[0]
            if name.family:
                patient_data['family_name'] = name.family
            if name.given and len(name.given) > 0:
                patient_data['given_name'] = ' '.join(name.given)
        
        if fhir_patient.birthDate:
            patient_data['birth_date'] = fhir_patient.birthDate
            
        if fhir_patient.gender:
            patient_data['gender'] = fhir_patient.gender
            
        if fhir_patient.identifier:
            patient_data['identifier'] = [id.dict() for id in fhir_patient.identifier]
        
        return patient_data
    
    def db_to_fhir_patient(self, db_patient: Patient):
        """Convert database Patient to FHIR Patient"""
        if db_patient.fhir_resource:
            return FHIRPatient.parse_obj(db_patient.fhir_resource)
        
        # Construct from database fields if no stored FHIR resource
        fhir_data = {
            "resourceType": "Patient",
            "id": db_patient.fhir_id
        }
        
        if db_patient.family_name or db_patient.given_name:
            name = {}
            if db_patient.family_name:
                name["family"] = db_patient.family_name
            if db_patient.given_name:
                name["given"] = db_patient.given_name.split()
            fhir_data["name"] = [name]
        
        if db_patient.birth_date:
            fhir_data["birthDate"] = db_patient.birth_date.strftime("%Y-%m-%d")
            
        if db_patient.gender:
            fhir_data["gender"] = db_patient.gender
            
        if db_patient.identifier:
            fhir_data["identifier"] = db_patient.identifier
        
        return FHIRPatient.parse_obj(fhir_data)
    
    def fhir_to_db_imaging_study(self, fhir_imaging_study: FHIRImagingStudy, patient_id: str):
        """Convert FHIR ImagingStudy to database ImagingStudy"""
        # Find patient
        patient = self.db_session.query(Patient).filter_by(fhir_id=patient_id).first()
        if not patient:
            raise ValueError(f"Patient {patient_id} not found")
        
        imaging_study_data = {
            'fhir_id': fhir_imaging_study.id or str(uuid.uuid4()),
            'patient_id': patient.id,
            'fhir_resource': fhir_imaging_study.dict()
        }
        
        # Extract commonly queried fields
        if fhir_imaging_study.identifier and len(fhir_imaging_study.identifier) > 0:
            imaging_study_data['study_instance_uid'] = fhir_imaging_study.identifier[0].value
            
        if fhir_imaging_study.modality and len(fhir_imaging_study.modality) > 0:
            imaging_study_data['modality'] = fhir_imaging_study.modality[0].code
            
        if fhir_imaging_study.description:
            imaging_study_data['description'] = fhir_imaging_study.description
            
        if fhir_imaging_study.started:
            imaging_study_data['started'] = fhir_imaging_study.started
        
        return imaging_study_data
    
    def db_to_fhir_imaging_study(self, db_imaging_study: ImagingStudy):
        """Convert database ImagingStudy to FHIR ImagingStudy"""
        if db_imaging_study.fhir_resource:
            return FHIRImagingStudy.parse_obj(db_imaging_study.fhir_resource)
        
        # Construct from database fields
        fhir_data = {
            "resourceType": "ImagingStudy",
            "id": db_imaging_study.fhir_id,
            "status": "available",
            "subject": {
                "reference": f"Patient/{db_imaging_study.patient.fhir_id}"
            }
        }
        
        if db_imaging_study.study_instance_uid:
            fhir_data["identifier"] = [{
                "value": db_imaging_study.study_instance_uid
            }]
            
        if db_imaging_study.modality:
            fhir_data["modality"] = [{
                "system": "http://dicom.nema.org/resources/ontology/DCM",
                "code": db_imaging_study.modality
            }]
            
        if db_imaging_study.description:
            fhir_data["description"] = db_imaging_study.description
            
        if db_imaging_study.started:
            fhir_data["started"] = db_imaging_study.started.isoformat()
        
        return FHIRImagingStudy.parse_obj(fhir_data)
    
    def create_diagnostic_report_from_ai_analysis(self, ai_analysis, dicom_study):
        """Create FHIR DiagnosticReport from AI analysis results"""
        try:
            # Get patient from dicom study
            patient = dicom_study.imaging_study.patient
            
            fhir_data = {
                "resourceType": "DiagnosticReport",
                "id": str(uuid.uuid4()),
                "status": "final",
                "category": [{
                    "coding": [{
                        "system": "http://terminology.hl7.org/CodeSystem/v2-0074",
                        "code": "RAD",
                        "display": "Radiology"
                    }]
                }],
                "code": {
                    "coding": [{
                        "system": "http://loinc.org",
                        "code": "18748-4",
                        "display": "Diagnostic imaging study"
                    }]
                },
                "subject": {
                    "reference": f"Patient/{patient.fhir_id}"
                },
                "effectiveDateTime": ai_analysis.created_at.isoformat(),
                "issued": ai_analysis.created_at.isoformat(),
                "performer": [{
                    "reference": "Organization/med-gemma-ai",
                    "display": "Med-Gemma AI System"
                }],
                "conclusion": ai_analysis.output_text,
                "conclusionCode": [{
                    "text": f"AI Analysis - {ai_analysis.analysis_type}"
                }]
            }
            
            # Add confidence score as extension
            if ai_analysis.confidence_score:
                fhir_data["extension"] = [{
                    "url": "http://medgemma.ai/fhir/StructureDefinition/confidence-score",
                    "valueDecimal": float(ai_analysis.confidence_score)
                }]
            
            diagnostic_report = FHIRDiagnosticReport.parse_obj(fhir_data)
            
            # Save to database
            db_diagnostic_report = DiagnosticReport(
                fhir_id=diagnostic_report.id,
                patient_id=patient.id,
                status=diagnostic_report.status,
                category="RAD",
                code="18748-4",
                effective_datetime=ai_analysis.created_at,
                conclusion=ai_analysis.output_text,
                fhir_resource=diagnostic_report.dict()
            )
            
            self.db_session.add(db_diagnostic_report)
            self.db_session.commit()
            
            logger.info(f"Created diagnostic report from AI analysis: {diagnostic_report.id}")
            return diagnostic_report.dict()
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error creating diagnostic report from AI analysis: {str(e)}")
            raise
    
    def validate_resource_reference(self, resource_type: str, resource_id: str):
        """Validate that a referenced resource exists"""
        if resource_type == "Patient":
            return self.db_session.query(Patient).filter_by(fhir_id=resource_id).first() is not None
        elif resource_type == "ImagingStudy":
            return self.db_session.query(ImagingStudy).filter_by(fhir_id=resource_id).first() is not None
        elif resource_type == "DiagnosticReport":
            return self.db_session.query(DiagnosticReport).filter_by(fhir_id=resource_id).first() is not None
        
        return False
    
    def get_resource_by_id(self, resource_type: str, resource_id: str):
        """Get a resource by type and ID"""
        if resource_type == "Patient":
            patient = self.db_session.query(Patient).filter_by(fhir_id=resource_id).first()
            return self.db_to_fhir_patient(patient) if patient else None
        elif resource_type == "ImagingStudy":
            imaging_study = self.db_session.query(ImagingStudy).filter_by(fhir_id=resource_id).first()
            return self.db_to_fhir_imaging_study(imaging_study) if imaging_study else None
        elif resource_type == "DiagnosticReport":
            diagnostic_report = self.db_session.query(DiagnosticReport).filter_by(fhir_id=resource_id).first()
            if diagnostic_report and diagnostic_report.fhir_resource:
                return FHIRDiagnosticReport.parse_obj(diagnostic_report.fhir_resource)
        
        return None