"""DICOM to FHIR resource converter"""

import uuid
import logging
from datetime import datetime
from typing import Dict, Any, Optional, List
from fhir.resources.patient import Patient as FHIRPatient
from fhir.resources.imagingstudy import ImagingStudy as FHIRImagingStudy
from fhir.resources.documentreference import DocumentReference as FHIRDocumentReference
from fhir.resources.identifier import Identifier
from fhir.resources.humanname import HumanName
from fhir.resources.coding import Coding
from fhir.resources.codeableconcept import CodeableConcept

logger = logging.getLogger(__name__)

class DicomToFhirConverter:
    """Converts DICOM metadata to FHIR resources"""
    
    def __init__(self):
        self.modality_codes = {
            'CT': {'code': 'CT', 'display': 'Computed Tomography'},
            'MR': {'code': 'MR', 'display': 'Magnetic Resonance'},
            'US': {'code': 'US', 'display': 'Ultrasound'},
            'XR': {'code': 'XA', 'display': 'X-Ray Angiography'},
            'CR': {'code': 'CR', 'display': 'Computed Radiography'},
            'DX': {'code': 'DX', 'display': 'Digital Radiography'},
            'MG': {'code': 'MG', 'display': 'Mammography'},
            'NM': {'code': 'NM', 'display': 'Nuclear Medicine'},
            'PT': {'code': 'PT', 'display': 'Positron emission tomography'},
            'RF': {'code': 'RF', 'display': 'Radio Fluoroscopy'},
        }
    
    def dicom_to_fhir_patient(self, dicom_metadata: Dict[str, Any]) -> Dict[str, Any]:
        """Convert DICOM patient information to FHIR Patient resource"""
        try:
            patient_id = dicom_metadata.get('patient_id', str(uuid.uuid4()))
            
            # Create FHIR Patient resource
            patient_data = {
                'resourceType': 'Patient',
                'id': patient_id,
                'identifier': []
            }
            
            # Add patient identifier
            if dicom_metadata.get('patient_id'):
                patient_data['identifier'].append({
                    'system': 'http://hospital.system/patient-id',
                    'value': dicom_metadata['patient_id']
                })
            
            # Add patient name
            if dicom_metadata.get('patient_name'):
                name_parts = str(dicom_metadata['patient_name']).split('^')
                name = {}
                
                if len(name_parts) > 0 and name_parts[0]:
                    name['family'] = name_parts[0]
                if len(name_parts) > 1 and name_parts[1]:
                    name['given'] = [name_parts[1]]
                if len(name_parts) > 2 and name_parts[2]:
                    if 'given' not in name:
                        name['given'] = []
                    name['given'].append(name_parts[2])
                
                if name:
                    patient_data['name'] = [name]
            
            # Add birth date
            if dicom_metadata.get('patient_birth_date'):
                patient_data['birthDate'] = dicom_metadata['patient_birth_date']
            
            # Add gender
            if dicom_metadata.get('patient_sex'):
                sex_mapping = {'M': 'male', 'F': 'female', 'O': 'other'}
                gender = sex_mapping.get(dicom_metadata['patient_sex'].upper())
                if gender:
                    patient_data['gender'] = gender
            
            logger.info(f"Converted DICOM to FHIR Patient: {patient_id}")
            return patient_data
            
        except Exception as e:
            logger.error(f"Error converting DICOM to FHIR Patient: {str(e)}")
            raise
    
    def dicom_to_fhir_imaging_study(self, dicom_metadata: Dict[str, Any]) -> Dict[str, Any]:
        """Convert DICOM study information to FHIR ImagingStudy resource"""
        try:
            study_id = str(uuid.uuid4())
            patient_id = dicom_metadata.get('patient_id', str(uuid.uuid4()))
            
            # Create FHIR ImagingStudy resource
            imaging_study_data = {
                'resourceType': 'ImagingStudy',
                'id': study_id,
                'status': 'available',
                'subject': {
                    'reference': f'Patient/{patient_id}'
                },
                'identifier': []
            }
            
            # Add study instance UID
            if dicom_metadata.get('study_instance_uid'):
                imaging_study_data['identifier'].append({
                    'system': 'urn:dicom:uid',
                    'value': f"urn:oid:{dicom_metadata['study_instance_uid']}"
                })
            
            # Add study date and time
            if dicom_metadata.get('study_date'):
                study_datetime = dicom_metadata['study_date']
                if dicom_metadata.get('study_time'):
                    # Combine date and time
                    time_str = dicom_metadata['study_time']
                    if len(time_str) >= 6:
                        hour = time_str[:2]
                        minute = time_str[2:4]
                        second = time_str[4:6]
                        study_datetime += f'T{hour}:{minute}:{second}'
                
                imaging_study_data['started'] = study_datetime
            
            # Add modalities
            if dicom_metadata.get('modality'):
                modality_code = self.modality_codes.get(dicom_metadata['modality'])
                if modality_code:
                    imaging_study_data['modality'] = [{
                        'system': 'http://dicom.nema.org/resources/ontology/DCM',
                        'code': modality_code['code'],
                        'display': modality_code['display']
                    }]
            
            # Add description
            if dicom_metadata.get('study_description'):
                imaging_study_data['description'] = dicom_metadata['study_description']
            
            # Add number of series and instances
            imaging_study_data['numberOfSeries'] = 1
            imaging_study_data['numberOfInstances'] = 1
            
            # Add series information
            series_data = self._create_series_from_dicom(dicom_metadata)
            if series_data:
                imaging_study_data['series'] = [series_data]
            
            logger.info(f"Converted DICOM to FHIR ImagingStudy: {study_id}")
            return imaging_study_data
            
        except Exception as e:
            logger.error(f"Error converting DICOM to FHIR ImagingStudy: {str(e)}")
            raise
    
    def _create_series_from_dicom(self, dicom_metadata: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Create FHIR ImagingStudy series from DICOM metadata"""
        try:
            series_data = {
                'uid': dicom_metadata.get('series_instance_uid', str(uuid.uuid4())),
                'number': 1,
                'numberOfInstances': 1
            }
            
            # Add modality
            if dicom_metadata.get('modality'):
                modality_code = self.modality_codes.get(dicom_metadata['modality'])
                if modality_code:
                    series_data['modality'] = {
                        'system': 'http://dicom.nema.org/resources/ontology/DCM',
                        'code': modality_code['code'],
                        'display': modality_code['display']
                    }
            
            # Add description
            if dicom_metadata.get('series_description'):
                series_data['description'] = dicom_metadata['series_description']
            
            # Add series date and time
            if dicom_metadata.get('series_date'):
                series_datetime = dicom_metadata['series_date']
                if dicom_metadata.get('series_time'):
                    time_str = dicom_metadata['series_time']
                    if len(time_str) >= 6:
                        hour = time_str[:2]
                        minute = time_str[2:4]
                        second = time_str[4:6]
                        series_datetime += f'T{hour}:{minute}:{second}'
                
                series_data['started'] = series_datetime
            
            # Add instance information
            instance_data = self._create_instance_from_dicom(dicom_metadata)
            if instance_data:
                series_data['instance'] = [instance_data]
            
            return series_data
            
        except Exception as e:
            logger.error(f"Error creating series from DICOM: {str(e)}")
            return None
    
    def _create_instance_from_dicom(self, dicom_metadata: Dict[str, Any]) -> Optional[Dict[str, Any]]:
        """Create FHIR ImagingStudy instance from DICOM metadata"""
        try:
            instance_data = {
                'uid': dicom_metadata.get('sop_instance_uid', str(uuid.uuid4())),
                'sopClass': {
                    'system': 'urn:ietf:rfc:3986',
                    'code': f"urn:oid:{dicom_metadata.get('sop_class_uid', '1.2.840.10008.5.1.4.1.1.2')}"
                },
                'number': 1
            }
            
            # Add title if available
            if dicom_metadata.get('series_description'):
                instance_data['title'] = dicom_metadata['series_description']
            
            return instance_data
            
        except Exception as e:
            logger.error(f"Error creating instance from DICOM: {str(e)}")
            return None
    
    def dicom_to_fhir_document_reference(self, dicom_metadata: Dict[str, Any], file_path: str) -> Dict[str, Any]:
        """Convert DICOM file to FHIR DocumentReference resource"""
        try:
            document_id = str(uuid.uuid4())
            patient_id = dicom_metadata.get('patient_id', str(uuid.uuid4()))
            
            # Create FHIR DocumentReference resource
            document_data = {
                'resourceType': 'DocumentReference',
                'id': document_id,
                'status': 'current',
                'type': {
                    'coding': [{
                        'system': 'http://loinc.org',
                        'code': '18748-4',
                        'display': 'Diagnostic imaging study'
                    }]
                },
                'category': [{
                    'coding': [{
                        'system': 'http://hl7.org/fhir/us/core/CodeSystem/us-core-documentreference-category',
                        'code': 'clinical-note',
                        'display': 'Clinical Note'
                    }]
                }],
                'subject': {
                    'reference': f'Patient/{patient_id}'
                },
                'date': datetime.utcnow().isoformat(),
                'author': [{
                    'display': dicom_metadata.get('institution_name', 'Unknown Institution')
                }],
                'content': [{
                    'attachment': {
                        'contentType': 'application/dicom',
                        'title': f"DICOM Image - {dicom_metadata.get('modality', 'Unknown')}",
                        'creation': dicom_metadata.get('study_date', datetime.utcnow().strftime('%Y-%m-%d')),
                        'size': dicom_metadata.get('file_size', 0),
                        'url': f'file://{file_path}'
                    }
                }]
            }
            
            # Add description
            if dicom_metadata.get('study_description'):
                document_data['description'] = dicom_metadata['study_description']
            
            # Add context
            document_data['context'] = {
                'encounter': [],
                'event': [{
                    'coding': [{
                        'system': 'http://terminology.hl7.org/CodeSystem/v3-ActCode',
                        'code': 'IMP',
                        'display': 'Inpatient encounter'
                    }]
                }],
                'period': {
                    'start': dicom_metadata.get('study_date', datetime.utcnow().strftime('%Y-%m-%d'))
                }
            }
            
            logger.info(f"Converted DICOM to FHIR DocumentReference: {document_id}")
            return document_data
            
        except Exception as e:
            logger.error(f"Error converting DICOM to FHIR DocumentReference: {str(e)}")
            raise
    
    def batch_convert_dicom_study(self, dicom_files_metadata: List[Dict[str, Any]]) -> Dict[str, Any]:
        """Convert multiple DICOM files from the same study to FHIR Bundle"""
        try:
            if not dicom_files_metadata:
                raise ValueError("No DICOM metadata provided")
            
            # Group by study instance UID
            studies = {}
            for metadata in dicom_files_metadata:
                study_uid = metadata.get('study_instance_uid')
                if study_uid not in studies:
                    studies[study_uid] = []
                studies[study_uid].append(metadata)
            
            # Create bundle entries
            bundle_entries = []
            
            for study_uid, study_metadata_list in studies.items():
                # Use first file's metadata for study-level information
                primary_metadata = study_metadata_list[0]
                
                # Create patient resource
                patient_resource = self.dicom_to_fhir_patient(primary_metadata)
                bundle_entries.append({
                    'resource': patient_resource,
                    'fullUrl': f"Patient/{patient_resource['id']}"
                })
                
                # Create imaging study resource
                imaging_study_resource = self.dicom_to_fhir_imaging_study(primary_metadata)
                
                # Update with multiple series if needed
                series_map = {}
                for metadata in study_metadata_list:
                    series_uid = metadata.get('series_instance_uid')
                    if series_uid not in series_map:
                        series_map[series_uid] = []
                    series_map[series_uid].append(metadata)
                
                # Update series and instances count
                imaging_study_resource['numberOfSeries'] = len(series_map)
                total_instances = sum(len(instances) for instances in series_map.values())
                imaging_study_resource['numberOfInstances'] = total_instances
                
                bundle_entries.append({
                    'resource': imaging_study_resource,
                    'fullUrl': f"ImagingStudy/{imaging_study_resource['id']}"
                })
            
            # Create FHIR Bundle
            bundle = {
                'resourceType': 'Bundle',
                'id': str(uuid.uuid4()),
                'type': 'collection',
                'timestamp': datetime.utcnow().isoformat(),
                'total': len(bundle_entries),
                'entry': bundle_entries
            }
            
            logger.info(f"Created FHIR Bundle with {len(bundle_entries)} resources")
            return bundle
            
        except Exception as e:
            logger.error(f"Error creating FHIR Bundle from DICOM studies: {str(e)}")
            raise