"""FHIR Resource validation utilities"""

from fhir.resources.patient import Patient as FHIRPatient
from fhir.resources.imagingstudy import ImagingStudy as FHIRImagingStudy
from fhir.resources.diagnosticreport import DiagnosticReport as FHIRDiagnosticReport
from fhir.resources.observation import Observation as FHIRObservation
from fhir.resources.documentreference import DocumentReference as FHIRDocumentReference
from pydantic import ValidationError
import logging
from typing import Dict, List, Any, Optional

logger = logging.getLogger(__name__)

class FHIRValidator:
    """FHIR resource validation utility"""
    
    def __init__(self):
        self.supported_resources = {
            'Patient': FHIRPatient,
            'ImagingStudy': FHIRImagingStudy,
            'DiagnosticReport': FHIRDiagnosticReport,
            'Observation': FHIRObservation,
            'DocumentReference': FHIRDocumentReference
        }
    
    def validate_resource(self, resource_data: Dict[str, Any]) -> Dict[str, Any]:
        """Validate a FHIR resource"""
        try:
            resource_type = resource_data.get('resourceType')
            if not resource_type:
                return {
                    'valid': False,
                    'errors': ['Missing resourceType field']
                }
            
            if resource_type not in self.supported_resources:
                return {
                    'valid': False,
                    'errors': [f'Unsupported resource type: {resource_type}']
                }
            
            # Validate using Pydantic model
            resource_class = self.supported_resources[resource_type]
            validated_resource = resource_class.parse_obj(resource_data)
            
            return {
                'valid': True,
                'resource': validated_resource,
                'errors': []
            }
            
        except ValidationError as e:
            error_messages = []
            for error in e.errors():
                field_path = ' -> '.join(str(loc) for loc in error['loc'])
                error_messages.append(f"{field_path}: {error['msg']}")
            
            return {
                'valid': False,
                'errors': error_messages
            }
        except Exception as e:
            return {
                'valid': False,
                'errors': [f'Validation error: {str(e)}']
            }
    
    def validate_patient(self, patient_data: Dict[str, Any]) -> Dict[str, Any]:
        """Validate FHIR Patient resource"""
        try:
            patient = FHIRPatient.parse_obj(patient_data)
            
            # Additional business rule validations
            errors = []
            warnings = []
            
            # Check required fields
            if not patient.id:
                errors.append("Patient ID is required")
            
            # Check name
            if not patient.name or len(patient.name) == 0:
                warnings.append("Patient name is recommended")
            else:
                for name in patient.name:
                    if not name.family and not name.given:
                        warnings.append("Patient name should have family or given name")
            
            # Check birth date
            if not patient.birthDate:
                warnings.append("Birth date is recommended for patient identification")
            
            # Check identifiers
            if not patient.identifier or len(patient.identifier) == 0:
                warnings.append("At least one identifier is recommended")
            
            return {
                'valid': len(errors) == 0,
                'resource': patient,
                'errors': errors,
                'warnings': warnings
            }
            
        except ValidationError as e:
            return {
                'valid': False,
                'errors': [str(error) for error in e.errors()],
                'warnings': []
            }
    
    def validate_imaging_study(self, imaging_study_data: Dict[str, Any]) -> Dict[str, Any]:
        """Validate FHIR ImagingStudy resource"""
        try:
            imaging_study = FHIRImagingStudy.parse_obj(imaging_study_data)
            
            errors = []
            warnings = []
            
            # Check required fields
            if not imaging_study.id:
                errors.append("ImagingStudy ID is required")
            
            if not imaging_study.status:
                errors.append("ImagingStudy status is required")
            
            if not imaging_study.subject:
                errors.append("ImagingStudy subject (patient reference) is required")
            
            # Check study identifiers
            if not imaging_study.identifier or len(imaging_study.identifier) == 0:
                warnings.append("Study identifier (Study Instance UID) is recommended")
            
            # Check modality
            if not imaging_study.modality or len(imaging_study.modality) == 0:
                warnings.append("At least one modality is recommended")
            
            # Validate series if present
            if imaging_study.series:
                for i, series in enumerate(imaging_study.series):
                    if not series.uid:
                        errors.append(f"Series {i}: Series Instance UID is required")
                    
                    if series.instance:
                        for j, instance in enumerate(series.instance):
                            if not instance.uid:
                                errors.append(f"Series {i}, Instance {j}: SOP Instance UID is required")
            
            return {
                'valid': len(errors) == 0,
                'resource': imaging_study,
                'errors': errors,
                'warnings': warnings
            }
            
        except ValidationError as e:
            return {
                'valid': False,
                'errors': [str(error) for error in e.errors()],
                'warnings': []
            }
    
    def validate_diagnostic_report(self, diagnostic_report_data: Dict[str, Any]) -> Dict[str, Any]:
        """Validate FHIR DiagnosticReport resource"""
        try:
            diagnostic_report = FHIRDiagnosticReport.parse_obj(diagnostic_report_data)
            
            errors = []
            warnings = []
            
            # Check required fields
            if not diagnostic_report.id:
                errors.append("DiagnosticReport ID is required")
            
            if not diagnostic_report.status:
                errors.append("DiagnosticReport status is required")
            
            if not diagnostic_report.code:
                errors.append("DiagnosticReport code is required")
            
            if not diagnostic_report.subject:
                errors.append("DiagnosticReport subject (patient reference) is required")
            
            # Check effective date
            if not diagnostic_report.effectiveDateTime and not diagnostic_report.effectivePeriod:
                warnings.append("Effective date/time is recommended")
            
            # Check results
            if not diagnostic_report.result and not diagnostic_report.conclusion and not diagnostic_report.conclusionCode:
                warnings.append("Results, conclusion, or conclusion codes are recommended")
            
            return {
                'valid': len(errors) == 0,
                'resource': diagnostic_report,
                'errors': errors,
                'warnings': warnings
            }
            
        except ValidationError as e:
            return {
                'valid': False,
                'errors': [str(error) for error in e.errors()],
                'warnings': []
            }
    
    def validate_reference(self, reference: str) -> Dict[str, Any]:
        """Validate FHIR resource reference format"""
        if not reference:
            return {
                'valid': False,
                'errors': ['Reference cannot be empty']
            }
        
        # Check reference format
        if '/' not in reference:
            return {
                'valid': False,
                'errors': ['Reference must be in format ResourceType/id']
            }
        
        resource_type, resource_id = reference.split('/', 1)
        
        if resource_type not in self.supported_resources:
            return {
                'valid': False,
                'errors': [f'Unsupported resource type in reference: {resource_type}']
            }
        
        if not resource_id:
            return {
                'valid': False,
                'errors': ['Resource ID cannot be empty in reference']
            }
        
        return {
            'valid': True,
            'resource_type': resource_type,
            'resource_id': resource_id,
            'errors': []
        }
    
    def validate_bundle(self, bundle_data: Dict[str, Any]) -> Dict[str, Any]:
        """Validate FHIR Bundle resource"""
        try:
            from fhir.resources.bundle import Bundle
            
            bundle = Bundle.parse_obj(bundle_data)
            
            errors = []
            warnings = []
            
            # Check bundle type
            if not bundle.type:
                errors.append("Bundle type is required")
            
            # Validate entries
            if bundle.entry:
                for i, entry in enumerate(bundle.entry):
                    if entry.resource:
                        # Validate individual resource
                        resource_validation = self.validate_resource(entry.resource.dict())
                        if not resource_validation['valid']:
                            for error in resource_validation['errors']:
                                errors.append(f"Entry {i}: {error}")
                    
                    # Check fullUrl if present
                    if entry.fullUrl:
                        if not entry.fullUrl.startswith(('http://', 'https://', 'urn:')):
                            warnings.append(f"Entry {i}: fullUrl should be an absolute URL or URN")
            
            return {
                'valid': len(errors) == 0,
                'resource': bundle,
                'errors': errors,
                'warnings': warnings
            }
            
        except ValidationError as e:
            return {
                'valid': False,
                'errors': [str(error) for error in e.errors()],
                'warnings': []
            }
        except Exception as e:
            return {
                'valid': False,
                'errors': [f'Bundle validation error: {str(e)}'],
                'warnings': []
            }