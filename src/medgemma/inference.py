"""Med-Gemma inference service"""

import logging
import asyncio
from typing import Dict, Any, Optional, List
from datetime import datetime
from sqlalchemy.orm import Session
from ..models import AIAnalysis, DicomStudy
from .model import MedGemmaModel

logger = logging.getLogger(__name__)

class MedGemmaInference:
    """Med-Gemma inference service for medical AI analysis"""
    
    def __init__(self, model_name: str = "google/medgemma-7b", model_path: str = None):
        self.model = MedGemmaModel(model_name, model_path)
        self.model.load_model()
    
    async def analyze_dicom_study(self, dicom_study_id: str, db_session: Session) -> Dict[str, Any]:
        """Analyze a DICOM study using Med-Gemma"""
        try:
            # Get DICOM study from database
            dicom_study = db_session.query(DicomStudy).filter_by(id=dicom_study_id).first()
            if not dicom_study:
                raise ValueError(f"DICOM study not found: {dicom_study_id}")
            
            # Prepare analysis prompt
            analysis_prompt = self._create_analysis_prompt(dicom_study)
            
            # Generate AI analysis
            start_time = datetime.utcnow()
            analysis_result = self.model.generate_response(
                analysis_prompt,
                context={
                    'dicom_study_id': dicom_study_id,
                    'modality': dicom_study.modality,
                    'analysis_type': 'comprehensive_analysis'
                }
            )
            
            # Save analysis to database
            ai_analysis = AIAnalysis(
                dicom_study_id=dicom_study.id,
                model_name=self.model.model_name,
                model_version=analysis_result.get('model_version', '1.0.0'),
                analysis_type='comprehensive_analysis',
                input_text=analysis_prompt,
                output_text=analysis_result.get('output_text', ''),
                confidence_score=str(analysis_result.get('confidence_score', 0.0)),
                processing_time=analysis_result.get('processing_time_ms', 0),
                status='completed' if analysis_result.get('status') == 'success' else 'failed',
                error_message=analysis_result.get('error')
            )
            
            db_session.add(ai_analysis)
            
            # Update DICOM study as processed
            dicom_study.processed = True
            
            db_session.commit()
            
            logger.info(f"Completed AI analysis for DICOM study: {dicom_study_id}")
            
            return {
                'analysis_id': str(ai_analysis.id),
                'dicom_study_id': dicom_study_id,
                'status': 'success',
                'result': analysis_result
            }
            
        except Exception as e:
            db_session.rollback()
            logger.error(f"Error analyzing DICOM study {dicom_study_id}: {str(e)}")
            return {
                'dicom_study_id': dicom_study_id,
                'status': 'error',
                'error': str(e)
            }
    
    def _create_analysis_prompt(self, dicom_study: DicomStudy) -> str:
        """Create analysis prompt from DICOM study metadata"""
        metadata = dicom_study.dicom_metadata or {}
        
        prompt_parts = [
            "Please analyze this medical imaging study:",
            "",
            f"Study Information:",
            f"- Modality: {dicom_study.modality or 'Unknown'}",
            f"- Study Date: {dicom_study.study_date or 'Unknown'}",
            f"- Study Description: {dicom_study.study_description or 'Not provided'}",
            f"- Institution: {dicom_study.institution_name or 'Unknown'}",
            ""
        ]
        
        # Add patient information if available
        if metadata.get('patient_age'):
            prompt_parts.append(f"- Patient Age: {metadata['patient_age']}")
        if metadata.get('patient_sex'):
            prompt_parts.append(f"- Patient Sex: {metadata['patient_sex']}")
        
        # Add technical parameters
        if metadata.get('manufacturer'):
            prompt_parts.append(f"- Equipment: {metadata['manufacturer']}")
        if metadata.get('kvp'):
            prompt_parts.append(f"- kVp: {metadata['kvp']}")
        if metadata.get('slice_thickness'):
            prompt_parts.append(f"- Slice Thickness: {metadata['slice_thickness']}mm")
        
        prompt_parts.extend([
            "",
            "Please provide:",
            "1. Technical quality assessment",
            "2. Anatomical findings",
            "3. Any notable observations",
            "4. Recommendations for further evaluation if needed",
            "",
            "Analysis:"
        ])
        
        return "\n".join(prompt_parts)
    
    async def batch_analyze_studies(self, dicom_study_ids: List[str], db_session: Session) -> List[Dict[str, Any]]:
        """Analyze multiple DICOM studies in batch"""
        results = []
        
        for study_id in dicom_study_ids:
            try:
                result = await self.analyze_dicom_study(study_id, db_session)
                results.append(result)
                
                # Add small delay to prevent overwhelming the system
                await asyncio.sleep(0.1)
                
            except Exception as e:
                logger.error(f"Error in batch analysis for study {study_id}: {str(e)}")
                results.append({
                    'dicom_study_id': study_id,
                    'status': 'error',
                    'error': str(e)
                })
        
        return results
    
    def generate_summary_report(self, analysis_ids: List[str], db_session: Session) -> Dict[str, Any]:
        """Generate summary report from multiple analyses"""
        try:
            analyses = db_session.query(AIAnalysis).filter(AIAnalysis.id.in_(analysis_ids)).all()
            
            if not analyses:
                return {'error': 'No analyses found'}
            
            # Aggregate results
            summary = {
                'total_analyses': len(analyses),
                'successful_analyses': len([a for a in analyses if a.status == 'completed']),
                'failed_analyses': len([a for a in analyses if a.status == 'failed']),
                'average_processing_time': sum(a.processing_time or 0 for a in analyses) / len(analyses),
                'modalities_analyzed': list(set(a.dicom_study.modality for a in analyses if a.dicom_study)),
                'timestamp': datetime.utcnow().isoformat(),
                'analyses': []
            }
            
            for analysis in analyses:
                summary['analyses'].append({
                    'analysis_id': str(analysis.id),
                    'dicom_study_id': str(analysis.dicom_study_id),
                    'modality': analysis.dicom_study.modality if analysis.dicom_study else 'Unknown',
                    'status': analysis.status,
                    'confidence_score': float(analysis.confidence_score) if analysis.confidence_score else 0.0,
                    'processing_time': analysis.processing_time,
                    'created_at': analysis.created_at.isoformat()
                })
            
            return summary
            
        except Exception as e:
            logger.error(f"Error generating summary report: {str(e)}")
            return {'error': str(e)}
    
    def get_analysis_by_id(self, analysis_id: str, db_session: Session) -> Optional[Dict[str, Any]]:
        """Get specific analysis results"""
        try:
            analysis = db_session.query(AIAnalysis).filter_by(id=analysis_id).first()
            if not analysis:
                return None
            
            return {
                'analysis_id': str(analysis.id),
                'dicom_study_id': str(analysis.dicom_study_id),
                'model_name': analysis.model_name,
                'model_version': analysis.model_version,
                'analysis_type': analysis.analysis_type,
                'input_text': analysis.input_text,
                'output_text': analysis.output_text,
                'confidence_score': float(analysis.confidence_score) if analysis.confidence_score else 0.0,
                'processing_time': analysis.processing_time,
                'status': analysis.status,
                'error_message': analysis.error_message,
                'created_at': analysis.created_at.isoformat(),
                'dicom_study': {
                    'modality': analysis.dicom_study.modality if analysis.dicom_study else None,
                    'study_description': analysis.dicom_study.study_description if analysis.dicom_study else None,
                    'study_date': analysis.dicom_study.study_date.isoformat() if analysis.dicom_study and analysis.dicom_study.study_date else None
                }
            }
            
        except Exception as e:
            logger.error(f"Error getting analysis {analysis_id}: {str(e)}")
            return None
    
    def get_model_status(self) -> Dict[str, Any]:
        """Get model status and capabilities"""
        return {
            'model_info': self.model.get_model_info(),
            'service_status': 'running',
            'capabilities': {
                'dicom_analysis': True,
                'batch_processing': True,
                'summary_reports': True,
                'real_time_inference': True
            },
            'performance': {
                'average_processing_time': 'Variable',
                'supported_modalities': ['CT', 'MR', 'XR', 'US', 'CR', 'DX'],
                'max_concurrent_analyses': 10
            }
        }