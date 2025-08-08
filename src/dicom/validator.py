"""DICOM validation utilities"""

import pydicom
import logging
from typing import Dict, Any, List, Optional
from datetime import datetime
from pathlib import Path

logger = logging.getLogger(__name__)

class DicomValidator:
    """DICOM file validation and quality checks"""
    
    def __init__(self):
        self.required_tags = [
            'SOPInstanceUID',
            'StudyInstanceUID', 
            'SeriesInstanceUID',
            'Modality',
            'PatientID'
        ]
        
        self.recommended_tags = [
            'PatientName',
            'PatientBirthDate',
            'PatientSex',
            'StudyDate',
            'StudyTime',
            'StudyDescription',
            'SeriesDescription',
            'InstitutionName',
            'Manufacturer'
        ]
        
        self.supported_modalities = [
            'CT', 'MR', 'US', 'XR', 'CR', 'DX', 'MG', 'NM', 'PT', 'RF', 'SC'
        ]
    
    def validate_dicom_file(self, file_path: str) -> Dict[str, Any]:
        """Comprehensive DICOM file validation"""
        validation_result = {
            'file_path': file_path,
            'is_valid': False,
            'is_dicom': False,
            'errors': [],
            'warnings': [],
            'info': {},
            'validation_timestamp': datetime.utcnow().isoformat()
        }
        
        try:
            # Check if file exists
            if not Path(file_path).exists():
                validation_result['errors'].append("File does not exist")
                return validation_result
            
            # Check file size
            file_size = Path(file_path).stat().st_size
            if file_size == 0:
                validation_result['errors'].append("File is empty")
                return validation_result
            
            if file_size > 500 * 1024 * 1024:  # 500MB
                validation_result['warnings'].append(f"Large file size: {file_size / (1024*1024):.1f} MB")
            
            # Try to read as DICOM
            try:
                dataset = pydicom.dcmread(file_path, force=True)
                validation_result['is_dicom'] = True
            except Exception as e:
                validation_result['errors'].append(f"Not a valid DICOM file: {str(e)}")
                return validation_result
            
            # Validate DICOM structure
            structure_validation = self._validate_dicom_structure(dataset)
            validation_result['errors'].extend(structure_validation['errors'])
            validation_result['warnings'].extend(structure_validation['warnings'])
            validation_result['info'].update(structure_validation['info'])
            
            # Validate DICOM content
            content_validation = self._validate_dicom_content(dataset)
            validation_result['errors'].extend(content_validation['errors'])
            validation_result['warnings'].extend(content_validation['warnings'])
            validation_result['info'].update(content_validation['info'])
            
            # Validate pixel data if present
            if hasattr(dataset, 'pixel_array'):
                pixel_validation = self._validate_pixel_data(dataset)
                validation_result['errors'].extend(pixel_validation['errors'])
                validation_result['warnings'].extend(pixel_validation['warnings'])
                validation_result['info'].update(pixel_validation['info'])
            
            # Set overall validation status
            validation_result['is_valid'] = len(validation_result['errors']) == 0
            
            if validation_result['is_valid']:
                logger.info(f"DICOM validation passed: {file_path}")
            else:
                logger.warning(f"DICOM validation failed: {file_path}, errors: {validation_result['errors']}")
            
            return validation_result
            
        except Exception as e:
            validation_result['errors'].append(f"Validation error: {str(e)}")
            logger.error(f"Error validating DICOM file {file_path}: {str(e)}")
            return validation_result
    
    def _validate_dicom_structure(self, dataset) -> Dict[str, Any]:
        """Validate DICOM file structure and required tags"""
        result = {
            'errors': [],
            'warnings': [],
            'info': {}
        }
        
        # Check required tags
        missing_required = []
        for tag in self.required_tags:
            if not hasattr(dataset, tag) or not getattr(dataset, tag):
                missing_required.append(tag)
        
        if missing_required:
            result['errors'].append(f"Missing required DICOM tags: {', '.join(missing_required)}")
        
        # Check recommended tags
        missing_recommended = []
        for tag in self.recommended_tags:
            if not hasattr(dataset, tag) or not getattr(dataset, tag):
                missing_recommended.append(tag)
        
        if missing_recommended:
            result['warnings'].append(f"Missing recommended DICOM tags: {', '.join(missing_recommended)}")
        
        # Check DICOM conformance
        if hasattr(dataset, 'file_meta'):
            if not hasattr(dataset.file_meta, 'TransferSyntaxUID'):
                result['warnings'].append("Missing Transfer Syntax UID in file meta information")
            
            if not hasattr(dataset.file_meta, 'MediaStorageSOPClassUID'):
                result['warnings'].append("Missing Media Storage SOP Class UID")
            
            if not hasattr(dataset.file_meta, 'MediaStorageSOPInstanceUID'):
                result['warnings'].append("Missing Media Storage SOP Instance UID")
        else:
            result['warnings'].append("Missing file meta information")
        
        # Store basic info
        result['info']['sop_class_uid'] = str(getattr(dataset, 'SOPClassUID', ''))
        result['info']['modality'] = str(getattr(dataset, 'Modality', ''))
        result['info']['manufacturer'] = str(getattr(dataset, 'Manufacturer', ''))
        
        return result
    
    def _validate_dicom_content(self, dataset) -> Dict[str, Any]:
        """Validate DICOM content and values"""
        result = {
            'errors': [],
            'warnings': [],
            'info': {}
        }
        
        # Validate modality
        modality = str(getattr(dataset, 'Modality', ''))
        if modality and modality not in self.supported_modalities:
            result['warnings'].append(f"Unsupported modality: {modality}")
        
        # Validate dates
        study_date = getattr(dataset, 'StudyDate', None)
        if study_date:
            if not self._validate_dicom_date(study_date):
                result['errors'].append(f"Invalid StudyDate format: {study_date}")
        
        series_date = getattr(dataset, 'SeriesDate', None)
        if series_date:
            if not self._validate_dicom_date(series_date):
                result['errors'].append(f"Invalid SeriesDate format: {series_date}")
        
        # Validate patient information
        patient_sex = str(getattr(dataset, 'PatientSex', ''))
        if patient_sex and patient_sex.upper() not in ['M', 'F', 'O', '']:
            result['warnings'].append(f"Invalid PatientSex value: {patient_sex}")
        
        # Validate UIDs format
        study_uid = str(getattr(dataset, 'StudyInstanceUID', ''))
        if study_uid and not self._validate_uid_format(study_uid):
            result['errors'].append("Invalid StudyInstanceUID format")
        
        series_uid = str(getattr(dataset, 'SeriesInstanceUID', ''))
        if series_uid and not self._validate_uid_format(series_uid):
            result['errors'].append("Invalid SeriesInstanceUID format")
        
        sop_uid = str(getattr(dataset, 'SOPInstanceUID', ''))
        if sop_uid and not self._validate_uid_format(sop_uid):
            result['errors'].append("Invalid SOPInstanceUID format")
        
        # Store content info
        result['info']['study_date'] = study_date
        result['info']['patient_id'] = str(getattr(dataset, 'PatientID', ''))
        result['info']['study_description'] = str(getattr(dataset, 'StudyDescription', ''))
        
        return result
    
    def _validate_pixel_data(self, dataset) -> Dict[str, Any]:
        """Validate pixel data if present"""
        result = {
            'errors': [],
            'warnings': [],
            'info': {}
        }
        
        try:
            if not hasattr(dataset, 'pixel_array'):
                return result
            
            pixel_array = dataset.pixel_array
            
            # Check image dimensions
            if hasattr(dataset, 'Rows') and hasattr(dataset, 'Columns'):
                expected_shape = (int(dataset.Rows), int(dataset.Columns))
                if pixel_array.shape[-2:] != expected_shape:
                    result['errors'].append(f"Pixel array shape mismatch: expected {expected_shape}, got {pixel_array.shape}")
            
            # Check bit depth
            if hasattr(dataset, 'BitsAllocated'):
                bits_allocated = int(dataset.BitsAllocated)
                if bits_allocated not in [8, 16, 32]:
                    result['warnings'].append(f"Unusual BitsAllocated value: {bits_allocated}")
            
            # Check pixel representation
            if hasattr(dataset, 'PixelRepresentation'):
                pixel_repr = int(dataset.PixelRepresentation)
                if pixel_repr not in [0, 1]:
                    result['errors'].append(f"Invalid PixelRepresentation: {pixel_repr}")
            
            # Check photometric interpretation
            if hasattr(dataset, 'PhotometricInterpretation'):
                photometric = str(dataset.PhotometricInterpretation)
                valid_photometric = [
                    'MONOCHROME1', 'MONOCHROME2', 'PALETTE COLOR', 
                    'RGB', 'HSV', 'ARGB', 'CMYK', 'YBR_FULL', 
                    'YBR_FULL_422', 'YBR_PARTIAL_422', 'YBR_PARTIAL_420'
                ]
                if photometric not in valid_photometric:
                    result['warnings'].append(f"Unsupported PhotometricInterpretation: {photometric}")
            
            # Store pixel info
            result['info']['pixel_shape'] = pixel_array.shape
            result['info']['pixel_dtype'] = str(pixel_array.dtype)
            result['info']['pixel_min'] = float(pixel_array.min())
            result['info']['pixel_max'] = float(pixel_array.max())
            result['info']['pixel_mean'] = float(pixel_array.mean())
            
        except Exception as e:
            result['errors'].append(f"Error validating pixel data: {str(e)}")
        
        return result
    
    def _validate_dicom_date(self, date_value) -> bool:
        """Validate DICOM date format (YYYYMMDD)"""
        try:
            date_str = str(date_value)
            if len(date_str) != 8:
                return False
            
            # Try to parse as date
            datetime.strptime(date_str, '%Y%m%d')
            return True
            
        except ValueError:
            return False
    
    def _validate_uid_format(self, uid: str) -> bool:
        """Validate DICOM UID format"""
        if not uid:
            return False
        
        # UID should contain only digits and dots
        if not all(c.isdigit() or c == '.' for c in uid):
            return False
        
        # Should not start or end with dot
        if uid.startswith('.') or uid.endswith('.'):
            return False
        
        # Should not have consecutive dots
        if '..' in uid:
            return False
        
        # Should have reasonable length
        if len(uid) > 64:
            return False
        
        return True
    
    def validate_dicom_series(self, file_paths: List[str]) -> Dict[str, Any]:
        """Validate a series of DICOM files for consistency"""
        result = {
            'series_valid': False,
            'total_files': len(file_paths),
            'valid_files': 0,
            'invalid_files': 0,
            'errors': [],
            'warnings': [],
            'series_info': {}
        }
        
        if not file_paths:
            result['errors'].append("No files provided for series validation")
            return result
        
        try:
            # Validate individual files first
            file_validations = []
            series_uid = None
            study_uid = None
            modality = None
            
            for file_path in file_paths:
                file_validation = self.validate_dicom_file(file_path)
                file_validations.append(file_validation)
                
                if file_validation['is_valid']:
                    result['valid_files'] += 1
                    
                    # Check series consistency
                    try:
                        dataset = pydicom.dcmread(file_path)
                        
                        current_series_uid = str(getattr(dataset, 'SeriesInstanceUID', ''))
                        current_study_uid = str(getattr(dataset, 'StudyInstanceUID', ''))
                        current_modality = str(getattr(dataset, 'Modality', ''))
                        
                        if series_uid is None:
                            series_uid = current_series_uid
                            study_uid = current_study_uid
                            modality = current_modality
                        else:
                            if current_series_uid != series_uid:
                                result['errors'].append(f"Series UID mismatch in file {file_path}")
                            if current_study_uid != study_uid:
                                result['errors'].append(f"Study UID mismatch in file {file_path}")
                            if current_modality != modality:
                                result['warnings'].append(f"Modality mismatch in file {file_path}")
                    
                    except Exception as e:
                        result['warnings'].append(f"Could not check consistency for {file_path}: {str(e)}")
                else:
                    result['invalid_files'] += 1
            
            # Store series information
            result['series_info'] = {
                'series_instance_uid': series_uid,
                'study_instance_uid': study_uid,
                'modality': modality,
                'file_validations': file_validations
            }
            
            # Determine overall series validity
            result['series_valid'] = (
                result['valid_files'] == result['total_files'] and
                len(result['errors']) == 0
            )
            
            logger.info(f"Series validation completed: {result['valid_files']}/{result['total_files']} files valid")
            return result
            
        except Exception as e:
            result['errors'].append(f"Series validation error: {str(e)}")
            logger.error(f"Error validating DICOM series: {str(e)}")
            return result