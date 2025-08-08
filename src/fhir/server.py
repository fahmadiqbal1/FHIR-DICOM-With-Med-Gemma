"""FHIR Server implementation"""

from flask import Flask, request, jsonify
from fhir.resources.patient import Patient as FHIRPatient
from fhir.resources.imagingstudy import ImagingStudy as FHIRImagingStudy
from fhir.resources.diagnosticreport import DiagnosticReport as FHIRDiagnosticReport
from fhir.resources.bundle import Bundle
from sqlalchemy.orm import sessionmaker
import logging
import json
from datetime import datetime
from ..models import Patient, ImagingStudy, DiagnosticReport, Base
from .validator import FHIRValidator
from .resources import ResourceManager

logger = logging.getLogger(__name__)

class FHIRServer:
    """FHIR Server implementation with CRUD operations"""
    
    def __init__(self, database_session):
        self.db_session = database_session
        self.validator = FHIRValidator()
        self.resource_manager = ResourceManager(database_session)
        
    def create_patient(self, fhir_patient_data):
        """Create a new FHIR Patient resource"""
        try:
            # Validate FHIR resource
            patient_resource = FHIRPatient.parse_obj(fhir_patient_data)
            
            # Check if patient already exists
            existing_patient = self.db_session.query(Patient).filter_by(
                fhir_id=patient_resource.id
            ).first()
            
            if existing_patient:
                raise ValueError(f"Patient with ID {patient_resource.id} already exists")
            
            # Extract data for database
            patient_data = {
                'fhir_id': patient_resource.id,
                'fhir_resource': patient_resource.dict()
            }
            
            if patient_resource.name and len(patient_resource.name) > 0:
                name = patient_resource.name[0]
                if name.family:
                    patient_data['family_name'] = name.family
                if name.given and len(name.given) > 0:
                    patient_data['given_name'] = ' '.join(name.given)
            
            if patient_resource.birthDate:
                patient_data['birth_date'] = patient_resource.birthDate
                
            if patient_resource.gender:
                patient_data['gender'] = patient_resource.gender
                
            if patient_resource.identifier:
                patient_data['identifier'] = [id.dict() for id in patient_resource.identifier]
            
            # Create database record
            db_patient = Patient(**patient_data)
            self.db_session.add(db_patient)
            self.db_session.commit()
            
            logger.info(f"Created patient with ID: {patient_resource.id}")
            return patient_resource.dict()
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error creating patient: {str(e)}")
            raise
    
    def get_patient(self, patient_id):
        """Retrieve a FHIR Patient resource"""
        try:
            patient = self.db_session.query(Patient).filter_by(fhir_id=patient_id).first()
            if not patient:
                return None
            
            return patient.fhir_resource
            
        except Exception as e:
            logger.error(f"Error retrieving patient {patient_id}: {str(e)}")
            raise
    
    def update_patient(self, patient_id, fhir_patient_data):
        """Update a FHIR Patient resource"""
        try:
            # Validate FHIR resource
            patient_resource = FHIRPatient.parse_obj(fhir_patient_data)
            
            # Find existing patient
            patient = self.db_session.query(Patient).filter_by(fhir_id=patient_id).first()
            if not patient:
                raise ValueError(f"Patient with ID {patient_id} not found")
            
            # Update database record
            patient.fhir_resource = patient_resource.dict()
            patient.updated_at = datetime.utcnow()
            
            # Update extracted fields
            if patient_resource.name and len(patient_resource.name) > 0:
                name = patient_resource.name[0]
                if name.family:
                    patient.family_name = name.family
                if name.given and len(name.given) > 0:
                    patient.given_name = ' '.join(name.given)
            
            self.db_session.commit()
            
            logger.info(f"Updated patient with ID: {patient_id}")
            return patient_resource.dict()
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error updating patient {patient_id}: {str(e)}")
            raise
    
    def delete_patient(self, patient_id):
        """Delete a FHIR Patient resource"""
        try:
            patient = self.db_session.query(Patient).filter_by(fhir_id=patient_id).first()
            if not patient:
                raise ValueError(f"Patient with ID {patient_id} not found")
            
            self.db_session.delete(patient)
            self.db_session.commit()
            
            logger.info(f"Deleted patient with ID: {patient_id}")
            return True
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error deleting patient {patient_id}: {str(e)}")
            raise
    
    def search_patients(self, search_params):
        """Search for FHIR Patient resources"""
        try:
            query = self.db_session.query(Patient)
            
            # Apply search filters
            if 'family' in search_params:
                query = query.filter(Patient.family_name.ilike(f"%{search_params['family']}%"))
            
            if 'given' in search_params:
                query = query.filter(Patient.given_name.ilike(f"%{search_params['given']}%"))
            
            if 'gender' in search_params:
                query = query.filter(Patient.gender == search_params['gender'])
                
            if 'birthdate' in search_params:
                query = query.filter(Patient.birth_date == search_params['birthdate'])
            
            # Execute query
            patients = query.all()
            
            # Create FHIR Bundle
            bundle_entries = []
            for patient in patients:
                bundle_entries.append({
                    "resource": patient.fhir_resource,
                    "fullUrl": f"Patient/{patient.fhir_id}"
                })
            
            bundle = {
                "resourceType": "Bundle",
                "type": "searchset",
                "total": len(bundle_entries),
                "entry": bundle_entries
            }
            
            return bundle
            
        except Exception as e:
            logger.error(f"Error searching patients: {str(e)}")
            raise
    
    def create_imaging_study(self, fhir_imaging_study_data):
        """Create a new FHIR ImagingStudy resource"""
        try:
            imaging_study_resource = FHIRImagingStudy.parse_obj(fhir_imaging_study_data)
            
            # Verify patient exists
            patient_ref = imaging_study_resource.subject.reference
            patient_id = patient_ref.split('/')[-1] if '/' in patient_ref else patient_ref
            
            patient = self.db_session.query(Patient).filter_by(fhir_id=patient_id).first()
            if not patient:
                raise ValueError(f"Referenced patient {patient_id} not found")
            
            # Create database record
            imaging_study_data = {
                'fhir_id': imaging_study_resource.id,
                'patient_id': patient.id,
                'study_instance_uid': imaging_study_resource.identifier[0].value if imaging_study_resource.identifier else None,
                'fhir_resource': imaging_study_resource.dict()
            }
            
            if imaging_study_resource.modality and len(imaging_study_resource.modality) > 0:
                imaging_study_data['modality'] = imaging_study_resource.modality[0].code
            
            if imaging_study_resource.description:
                imaging_study_data['description'] = imaging_study_resource.description
                
            if imaging_study_resource.started:
                imaging_study_data['started'] = imaging_study_resource.started
            
            db_imaging_study = ImagingStudy(**imaging_study_data)
            self.db_session.add(db_imaging_study)
            self.db_session.commit()
            
            logger.info(f"Created imaging study with ID: {imaging_study_resource.id}")
            return imaging_study_resource.dict()
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error creating imaging study: {str(e)}")
            raise
    
    def get_capability_statement(self):
        """Return FHIR CapabilityStatement"""
        return {
            "resourceType": "CapabilityStatement",
            "status": "active",
            "date": "2025-01-01",
            "publisher": "FHIR-DICOM-Med-Gemma System",
            "kind": "instance",
            "software": {
                "name": "FHIR-DICOM-Med-Gemma",
                "version": "1.0.0"
            },
            "implementation": {
                "description": "FHIR Server for DICOM integration with Med-Gemma AI"
            },
            "fhirVersion": "4.0.1",
            "format": ["json"],
            "rest": [{
                "mode": "server",
                "resource": [
                    {
                        "type": "Patient",
                        "interaction": [
                            {"code": "create"},
                            {"code": "read"},
                            {"code": "update"},
                            {"code": "delete"},
                            {"code": "search-type"}
                        ],
                        "searchParam": [
                            {"name": "family", "type": "string"},
                            {"name": "given", "type": "string"},
                            {"name": "gender", "type": "token"},
                            {"name": "birthdate", "type": "date"}
                        ]
                    },
                    {
                        "type": "ImagingStudy",
                        "interaction": [
                            {"code": "create"},
                            {"code": "read"},
                            {"code": "search-type"}
                        ]
                    },
                    {
                        "type": "DiagnosticReport",
                        "interaction": [
                            {"code": "create"},
                            {"code": "read"},
                            {"code": "search-type"}
                        ]
                    }
                ]
            }]
        }