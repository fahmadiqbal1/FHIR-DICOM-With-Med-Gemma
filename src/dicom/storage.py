"""DICOM file storage and management"""

import os
import shutil
import hashlib
import logging
from pathlib import Path
from datetime import datetime
from typing import Dict, Any, Optional, List, Union
from sqlalchemy.orm import Session
from ..models import DicomStudy, ImagingStudy, Patient

logger = logging.getLogger(__name__)

class DicomStorage:
    """DICOM file storage and organization system"""
    
    def __init__(self, base_storage_path: str, db_session: Session):
        self.base_path = Path(base_storage_path)
        self.db_session = db_session
        self.max_file_size = 104857600  # 100MB default
        
        # Create base storage directory
        self.base_path.mkdir(parents=True, exist_ok=True)
        
        # Create subdirectories
        self.incoming_path = self.base_path / "incoming"
        self.processed_path = self.base_path / "processed"
        self.thumbnails_path = self.base_path / "thumbnails"
        self.temp_path = self.base_path / "temp"
        
        for path in [self.incoming_path, self.processed_path, self.thumbnails_path, self.temp_path]:
            path.mkdir(parents=True, exist_ok=True)
    
    def store_dicom_file(self, file_path: str, dicom_metadata: Dict[str, Any], 
                        patient_id: str = None, study_id: str = None) -> Dict[str, Any]:
        """Store DICOM file in organized directory structure"""
        try:
            if not os.path.exists(file_path):
                raise FileNotFoundError(f"Source file not found: {file_path}")
            
            # Validate file size
            file_size = os.path.getsize(file_path)
            if file_size > self.max_file_size:
                raise ValueError(f"File size ({file_size} bytes) exceeds maximum allowed ({self.max_file_size} bytes)")
            
            # Generate file hash for integrity checking
            file_hash = self._calculate_file_hash(file_path)
            
            # Create organized directory structure
            storage_info = self._create_storage_path(dicom_metadata, patient_id, study_id)
            
            # Copy file to storage location
            target_path = storage_info['target_path']
            target_path.parent.mkdir(parents=True, exist_ok=True)
            
            shutil.copy2(file_path, target_path)
            
            # Verify file integrity after copy
            copied_hash = self._calculate_file_hash(str(target_path))
            if file_hash != copied_hash:
                # Clean up and raise error
                target_path.unlink(missing_ok=True)
                raise RuntimeError("File integrity check failed after copying")
            
            # Update storage info
            storage_info.update({
                'original_path': file_path,
                'stored_path': str(target_path),
                'file_size': file_size,
                'file_hash': file_hash,
                'stored_at': datetime.utcnow().isoformat(),
                'status': 'stored'
            })
            
            # Save to database
            self._save_dicom_record(dicom_metadata, storage_info)
            
            logger.info(f"Successfully stored DICOM file: {target_path}")
            return storage_info
            
        except Exception as e:
            logger.error(f"Error storing DICOM file {file_path}: {str(e)}")
            raise
    
    def _create_storage_path(self, dicom_metadata: Dict[str, Any], 
                           patient_id: str = None, study_id: str = None) -> Dict[str, Any]:
        """Create organized storage path based on DICOM metadata"""
        
        # Extract identifiers
        patient_id = patient_id or dicom_metadata.get('patient_id', 'unknown_patient')
        study_instance_uid = dicom_metadata.get('study_instance_uid', 'unknown_study')
        series_instance_uid = dicom_metadata.get('series_instance_uid', 'unknown_series')
        sop_instance_uid = dicom_metadata.get('sop_instance_uid', 'unknown_instance')
        
        # Sanitize identifiers for filesystem
        patient_id = self._sanitize_filename(patient_id)
        study_instance_uid = self._sanitize_filename(study_instance_uid)
        series_instance_uid = self._sanitize_filename(series_instance_uid)
        sop_instance_uid = self._sanitize_filename(sop_instance_uid)
        
        # Create directory structure: processed/patient_id/study_uid/series_uid/
        study_date = dicom_metadata.get('study_date', datetime.now().strftime('%Y%m%d'))
        modality = dicom_metadata.get('modality', 'unknown')
        
        target_dir = (self.processed_path / 
                     patient_id / 
                     f"{study_date}_{study_instance_uid}" / 
                     f"{modality}_{series_instance_uid}")
        
        # Generate filename
        filename = f"{sop_instance_uid}.dcm"
        target_path = target_dir / filename
        
        return {
            'target_path': target_path,
            'target_dir': str(target_dir),
            'filename': filename,
            'relative_path': str(target_path.relative_to(self.base_path)),
            'patient_folder': patient_id,
            'study_folder': f"{study_date}_{study_instance_uid}",
            'series_folder': f"{modality}_{series_instance_uid}"
        }
    
    def _sanitize_filename(self, filename: str) -> str:
        """Sanitize filename for filesystem compatibility"""
        # Replace problematic characters
        invalid_chars = '<>:"/\\|?*'
        for char in invalid_chars:
            filename = filename.replace(char, '_')
        
        # Limit length
        max_length = 100
        if len(filename) > max_length:
            filename = filename[:max_length]
        
        return filename
    
    def _calculate_file_hash(self, file_path: str) -> str:
        """Calculate SHA-256 hash of file for integrity checking"""
        hash_sha256 = hashlib.sha256()
        with open(file_path, "rb") as f:
            for chunk in iter(lambda: f.read(4096), b""):
                hash_sha256.update(chunk)
        return hash_sha256.hexdigest()
    
    def _save_dicom_record(self, dicom_metadata: Dict[str, Any], storage_info: Dict[str, Any]):
        """Save DICOM record to database"""
        try:
            # Find or create patient
            patient_id = dicom_metadata.get('patient_id')
            patient = None
            if patient_id:
                patient = self.db_session.query(Patient).filter_by(fhir_id=patient_id).first()
            
            # Find or create imaging study
            study_instance_uid = dicom_metadata.get('study_instance_uid')
            imaging_study = None
            if study_instance_uid and patient:
                imaging_study = (self.db_session.query(ImagingStudy)
                               .filter_by(study_instance_uid=study_instance_uid, patient_id=patient.id)
                               .first())
            
            # Create DICOM study record
            dicom_study = DicomStudy(
                imaging_study_id=imaging_study.id if imaging_study else None,
                study_instance_uid=study_instance_uid,
                study_date=self._parse_dicom_date(dicom_metadata.get('study_date')),
                study_time=dicom_metadata.get('study_time'),
                study_description=dicom_metadata.get('study_description'),
                modality=dicom_metadata.get('modality'),
                institution_name=dicom_metadata.get('institution_name'),
                file_path=storage_info['stored_path'],
                file_size=storage_info['file_size'],
                dicom_metadata=dicom_metadata,
                processed=False
            )
            
            self.db_session.add(dicom_study)
            self.db_session.commit()
            
            logger.info(f"Saved DICOM record to database: {dicom_study.id}")
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error saving DICOM record to database: {str(e)}")
            raise
    
    def _parse_dicom_date(self, dicom_date) -> Optional[datetime]:
        """Parse DICOM date to datetime object"""
        if not dicom_date:
            return None
        
        try:
            date_str = str(dicom_date)
            if len(date_str) == 8:
                return datetime.strptime(date_str, '%Y%m%d')
        except Exception:
            pass
        
        return None
    
    def retrieve_dicom_file(self, dicom_study_id: str) -> Optional[str]:
        """Retrieve path to stored DICOM file"""
        try:
            dicom_study = self.db_session.query(DicomStudy).filter_by(id=dicom_study_id).first()
            if not dicom_study:
                return None
            
            file_path = dicom_study.file_path
            if file_path and os.path.exists(file_path):
                return file_path
            
            logger.warning(f"DICOM file not found: {file_path}")
            return None
            
        except Exception as e:
            logger.error(f"Error retrieving DICOM file for study {dicom_study_id}: {str(e)}")
            return None
    
    def delete_dicom_file(self, dicom_study_id: str, remove_file: bool = True) -> bool:
        """Delete DICOM file and database record"""
        try:
            dicom_study = self.db_session.query(DicomStudy).filter_by(id=dicom_study_id).first()
            if not dicom_study:
                logger.warning(f"DICOM study not found: {dicom_study_id}")
                return False
            
            # Remove file if requested and exists
            if remove_file and dicom_study.file_path and os.path.exists(dicom_study.file_path):
                os.remove(dicom_study.file_path)
                logger.info(f"Removed DICOM file: {dicom_study.file_path}")
            
            # Remove database record
            self.db_session.delete(dicom_study)
            self.db_session.commit()
            
            logger.info(f"Deleted DICOM study record: {dicom_study_id}")
            return True
            
        except Exception as e:
            self.db_session.rollback()
            logger.error(f"Error deleting DICOM file {dicom_study_id}: {str(e)}")
            return False
    
    def cleanup_temp_files(self, max_age_hours: int = 24) -> int:
        """Clean up temporary files older than specified age"""
        try:
            cleanup_count = 0
            current_time = datetime.now()
            
            for temp_file in self.temp_path.glob('*'):
                if temp_file.is_file():
                    file_age = current_time - datetime.fromtimestamp(temp_file.stat().st_mtime)
                    if file_age.total_seconds() > (max_age_hours * 3600):
                        temp_file.unlink()
                        cleanup_count += 1
                        logger.debug(f"Cleaned up temp file: {temp_file}")
            
            logger.info(f"Cleaned up {cleanup_count} temporary files")
            return cleanup_count
            
        except Exception as e:
            logger.error(f"Error cleaning up temp files: {str(e)}")
            return 0
    
    def get_storage_statistics(self) -> Dict[str, Any]:
        """Get storage usage statistics"""
        try:
            stats = {
                'base_path': str(self.base_path),
                'total_files': 0,
                'total_size': 0,
                'by_modality': {},
                'by_patient': {},
                'processed_files': 0,
                'unprocessed_files': 0
            }
            
            # Count files and calculate sizes
            for root, dirs, files in os.walk(self.processed_path):
                for file in files:
                    if file.endswith('.dcm'):
                        file_path = Path(root) / file
                        file_size = file_path.stat().st_size
                        stats['total_files'] += 1
                        stats['total_size'] += file_size
            
            # Get database statistics
            total_studies = self.db_session.query(DicomStudy).count()
            processed_studies = self.db_session.query(DicomStudy).filter_by(processed=True).count()
            
            stats['total_database_records'] = total_studies
            stats['processed_files'] = processed_studies
            stats['unprocessed_files'] = total_studies - processed_studies
            
            # Format size in human readable format
            stats['total_size_formatted'] = self._format_file_size(stats['total_size'])
            
            return stats
            
        except Exception as e:
            logger.error(f"Error getting storage statistics: {str(e)}")
            return {}
    
    def _format_file_size(self, size_bytes: int) -> str:
        """Format file size in human readable format"""
        if size_bytes == 0:
            return "0 B"
        
        for unit in ['B', 'KB', 'MB', 'GB', 'TB']:
            if size_bytes < 1024.0:
                return f"{size_bytes:.1f} {unit}"
            size_bytes /= 1024.0
        
        return f"{size_bytes:.1f} PB"
    
    def validate_storage_integrity(self) -> Dict[str, Any]:
        """Validate integrity of stored DICOM files"""
        try:
            validation_results = {
                'total_checked': 0,
                'valid_files': 0,
                'invalid_files': 0,
                'missing_files': 0,
                'corrupted_files': [],
                'missing_files_list': []
            }
            
            # Check all DICOM records in database
            dicom_studies = self.db_session.query(DicomStudy).all()
            
            for study in dicom_studies:
                validation_results['total_checked'] += 1
                
                if not study.file_path:
                    validation_results['missing_files'] += 1
                    validation_results['missing_files_list'].append({
                        'study_id': str(study.id),
                        'reason': 'No file path in database'
                    })
                    continue
                
                if not os.path.exists(study.file_path):
                    validation_results['missing_files'] += 1
                    validation_results['missing_files_list'].append({
                        'study_id': str(study.id),
                        'file_path': study.file_path,
                        'reason': 'File not found on disk'
                    })
                    continue
                
                # Check file size
                actual_size = os.path.getsize(study.file_path)
                if study.file_size and actual_size != study.file_size:
                    validation_results['invalid_files'] += 1
                    validation_results['corrupted_files'].append({
                        'study_id': str(study.id),
                        'file_path': study.file_path,
                        'reason': f'Size mismatch: expected {study.file_size}, actual {actual_size}'
                    })
                    continue
                
                validation_results['valid_files'] += 1
            
            logger.info(f"Storage validation completed: {validation_results['valid_files']}/{validation_results['total_checked']} files valid")
            return validation_results
            
        except Exception as e:
            logger.error(f"Error validating storage integrity: {str(e)}")
            return {'error': str(e)}