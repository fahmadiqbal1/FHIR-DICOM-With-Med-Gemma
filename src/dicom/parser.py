"""DICOM file parser and metadata extraction"""

import pydicom
import logging
import os
import json
from pathlib import Path
from datetime import datetime
from typing import Dict, Any, Optional, List
from PIL import Image
import numpy as np

logger = logging.getLogger(__name__)

class DicomParser:
    """DICOM file parser for extracting metadata and converting to FHIR"""
    
    def __init__(self):
        self.supported_modalities = ['CT', 'MR', 'US', 'XR', 'CR', 'DX', 'MG', 'NM', 'PT', 'RF']
    
    def parse_dicom_file(self, file_path: str) -> Dict[str, Any]:
        """Parse a DICOM file and extract metadata"""
        try:
            if not os.path.exists(file_path):
                raise FileNotFoundError(f"DICOM file not found: {file_path}")
            
            # Read DICOM file
            dicom_dataset = pydicom.dcmread(file_path)
            
            # Extract basic metadata
            metadata = {
                'file_path': file_path,
                'file_size': os.path.getsize(file_path),
                'parsed_at': datetime.utcnow().isoformat(),
                'sop_class_uid': str(dicom_dataset.get('SOPClassUID', '')),
                'sop_instance_uid': str(dicom_dataset.get('SOPInstanceUID', '')),
                'study_instance_uid': str(dicom_dataset.get('StudyInstanceUID', '')),
                'series_instance_uid': str(dicom_dataset.get('SeriesInstanceUID', '')),
                'modality': str(dicom_dataset.get('Modality', '')),
                'patient_id': str(dicom_dataset.get('PatientID', '')),
                'patient_name': str(dicom_dataset.get('PatientName', '')),
                'patient_birth_date': self._parse_dicom_date(dicom_dataset.get('PatientBirthDate')),
                'patient_sex': str(dicom_dataset.get('PatientSex', '')),
                'study_date': self._parse_dicom_date(dicom_dataset.get('StudyDate')),
                'study_time': str(dicom_dataset.get('StudyTime', '')),
                'study_description': str(dicom_dataset.get('StudyDescription', '')),
                'series_date': self._parse_dicom_date(dicom_dataset.get('SeriesDate')),
                'series_time': str(dicom_dataset.get('SeriesTime', '')),
                'series_description': str(dicom_dataset.get('SeriesDescription', '')),
                'institution_name': str(dicom_dataset.get('InstitutionName', '')),
                'manufacturer': str(dicom_dataset.get('Manufacturer', '')),
                'manufacturer_model_name': str(dicom_dataset.get('ManufacturerModelName', '')),
                'slice_thickness': self._get_numeric_value(dicom_dataset.get('SliceThickness')),
                'pixel_spacing': self._get_pixel_spacing(dicom_dataset),
                'image_orientation': self._get_image_orientation(dicom_dataset),
                'image_position': self._get_image_position(dicom_dataset),
                'rows': int(dicom_dataset.get('Rows', 0)),
                'columns': int(dicom_dataset.get('Columns', 0)),
                'bits_allocated': int(dicom_dataset.get('BitsAllocated', 0)),
                'bits_stored': int(dicom_dataset.get('BitsStored', 0)),
                'high_bit': int(dicom_dataset.get('HighBit', 0)),
                'photometric_interpretation': str(dicom_dataset.get('PhotometricInterpretation', '')),
                'samples_per_pixel': int(dicom_dataset.get('SamplesPerPixel', 1)),
                'window_center': self._get_numeric_value(dicom_dataset.get('WindowCenter')),
                'window_width': self._get_numeric_value(dicom_dataset.get('WindowWidth')),
                'rescale_intercept': self._get_numeric_value(dicom_dataset.get('RescaleIntercept')),
                'rescale_slope': self._get_numeric_value(dicom_dataset.get('RescaleSlope')),
            }
            
            # Add acquisition-specific metadata
            if metadata['modality'] == 'CT':
                metadata.update(self._extract_ct_metadata(dicom_dataset))
            elif metadata['modality'] == 'MR':
                metadata.update(self._extract_mr_metadata(dicom_dataset))
            elif metadata['modality'] in ['XR', 'CR', 'DX']:
                metadata.update(self._extract_xray_metadata(dicom_dataset))
            
            # Validate required fields
            validation_result = self._validate_dicom_metadata(metadata)
            metadata['validation'] = validation_result
            
            logger.info(f"Successfully parsed DICOM file: {file_path}")
            return metadata
            
        except Exception as e:
            logger.error(f"Error parsing DICOM file {file_path}: {str(e)}")
            raise
    
    def _parse_dicom_date(self, dicom_date) -> Optional[str]:
        """Parse DICOM date format (YYYYMMDD) to ISO format"""
        if not dicom_date:
            return None
        
        try:
            date_str = str(dicom_date)
            if len(date_str) == 8:
                year = date_str[:4]
                month = date_str[4:6]
                day = date_str[6:8]
                return f"{year}-{month}-{day}"
        except Exception:
            pass
        
        return None
    
    def _get_numeric_value(self, value) -> Optional[float]:
        """Safely extract numeric value from DICOM element"""
        if value is None:
            return None
        
        try:
            if hasattr(value, 'value'):
                return float(value.value)
            return float(value)
        except (ValueError, TypeError):
            return None
    
    def _get_pixel_spacing(self, dicom_dataset) -> Optional[List[float]]:
        """Extract pixel spacing"""
        try:
            pixel_spacing = dicom_dataset.get('PixelSpacing')
            if pixel_spacing:
                return [float(x) for x in pixel_spacing]
        except Exception:
            pass
        return None
    
    def _get_image_orientation(self, dicom_dataset) -> Optional[List[float]]:
        """Extract image orientation"""
        try:
            orientation = dicom_dataset.get('ImageOrientationPatient')
            if orientation:
                return [float(x) for x in orientation]
        except Exception:
            pass
        return None
    
    def _get_image_position(self, dicom_dataset) -> Optional[List[float]]:
        """Extract image position"""
        try:
            position = dicom_dataset.get('ImagePositionPatient')
            if position:
                return [float(x) for x in position]
        except Exception:
            pass
        return None
    
    def _extract_ct_metadata(self, dicom_dataset) -> Dict[str, Any]:
        """Extract CT-specific metadata"""
        return {
            'kvp': self._get_numeric_value(dicom_dataset.get('KVP')),
            'tube_current': self._get_numeric_value(dicom_dataset.get('XRayTubeCurrent')),
            'exposure_time': self._get_numeric_value(dicom_dataset.get('ExposureTime')),
            'filter_type': str(dicom_dataset.get('FilterType', '')),
            'convolution_kernel': str(dicom_dataset.get('ConvolutionKernel', '')),
            'contrast_bolus_agent': str(dicom_dataset.get('ContrastBolusAgent', '')),
            'scan_options': str(dicom_dataset.get('ScanOptions', '')),
        }
    
    def _extract_mr_metadata(self, dicom_dataset) -> Dict[str, Any]:
        """Extract MR-specific metadata"""
        return {
            'magnetic_field_strength': self._get_numeric_value(dicom_dataset.get('MagneticFieldStrength')),
            'repetition_time': self._get_numeric_value(dicom_dataset.get('RepetitionTime')),
            'echo_time': self._get_numeric_value(dicom_dataset.get('EchoTime')),
            'inversion_time': self._get_numeric_value(dicom_dataset.get('InversionTime')),
            'flip_angle': self._get_numeric_value(dicom_dataset.get('FlipAngle')),
            'sequence_name': str(dicom_dataset.get('SequenceName', '')),
            'scanning_sequence': str(dicom_dataset.get('ScanningSequence', '')),
            'sequence_variant': str(dicom_dataset.get('SequenceVariant', '')),
            'mr_acquisition_type': str(dicom_dataset.get('MRAcquisitionType', '')),
        }
    
    def _extract_xray_metadata(self, dicom_dataset) -> Dict[str, Any]:
        """Extract X-ray specific metadata"""
        return {
            'kvp': self._get_numeric_value(dicom_dataset.get('KVP')),
            'mas': self._get_numeric_value(dicom_dataset.get('Exposure')),
            'exposure_time': self._get_numeric_value(dicom_dataset.get('ExposureTime')),
            'tube_current': self._get_numeric_value(dicom_dataset.get('XRayTubeCurrent')),
            'filter_type': str(dicom_dataset.get('FilterType', '')),
            'grid': str(dicom_dataset.get('Grid', '')),
            'body_part_examined': str(dicom_dataset.get('BodyPartExamined', '')),
            'view_position': str(dicom_dataset.get('ViewPosition', '')),
        }
    
    def _validate_dicom_metadata(self, metadata: Dict[str, Any]) -> Dict[str, Any]:
        """Validate DICOM metadata for completeness"""
        errors = []
        warnings = []
        
        # Check required fields
        required_fields = ['sop_instance_uid', 'study_instance_uid', 'series_instance_uid', 'modality']
        for field in required_fields:
            if not metadata.get(field):
                errors.append(f"Missing required field: {field}")
        
        # Check modality support
        if metadata.get('modality') and metadata['modality'] not in self.supported_modalities:
            warnings.append(f"Unsupported modality: {metadata['modality']}")
        
        # Check patient information
        if not metadata.get('patient_id'):
            warnings.append("Missing patient ID")
        
        if not metadata.get('patient_name'):
            warnings.append("Missing patient name")
        
        # Check study information
        if not metadata.get('study_date'):
            warnings.append("Missing study date")
        
        if not metadata.get('study_description'):
            warnings.append("Missing study description")
        
        # Check image dimensions
        if not metadata.get('rows') or not metadata.get('columns'):
            errors.append("Missing image dimensions (rows/columns)")
        
        return {
            'valid': len(errors) == 0,
            'errors': errors,
            'warnings': warnings
        }
    
    def extract_pixel_data(self, file_path: str, normalize: bool = True) -> Optional[np.ndarray]:
        """Extract pixel data from DICOM file"""
        try:
            dicom_dataset = pydicom.dcmread(file_path)
            
            if not hasattr(dicom_dataset, 'pixel_array'):
                logger.warning(f"No pixel data found in DICOM file: {file_path}")
                return None
            
            pixel_data = dicom_dataset.pixel_array
            
            if normalize and pixel_data.dtype != np.uint8:
                # Normalize to 0-255 range for visualization
                pixel_data = pixel_data.astype(np.float64)
                pixel_data = (pixel_data - pixel_data.min()) / (pixel_data.max() - pixel_data.min()) * 255
                pixel_data = pixel_data.astype(np.uint8)
            
            return pixel_data
            
        except Exception as e:
            logger.error(f"Error extracting pixel data from {file_path}: {str(e)}")
            return None
    
    def generate_thumbnail(self, file_path: str, output_path: str, size: tuple = (256, 256)) -> bool:
        """Generate thumbnail image from DICOM file"""
        try:
            pixel_data = self.extract_pixel_data(file_path, normalize=True)
            if pixel_data is None:
                return False
            
            # Handle multi-frame or 3D data
            if len(pixel_data.shape) > 2:
                # Take middle slice for 3D data
                if len(pixel_data.shape) == 3:
                    pixel_data = pixel_data[pixel_data.shape[0] // 2]
                else:
                    pixel_data = pixel_data[0]  # Take first frame
            
            # Convert to PIL Image
            image = Image.fromarray(pixel_data)
            
            # Resize to thumbnail size
            image.thumbnail(size, Image.Resampling.LANCZOS)
            
            # Save thumbnail
            os.makedirs(os.path.dirname(output_path), exist_ok=True)
            image.save(output_path, format='PNG')
            
            logger.info(f"Generated thumbnail: {output_path}")
            return True
            
        except Exception as e:
            logger.error(f"Error generating thumbnail for {file_path}: {str(e)}")
            return False