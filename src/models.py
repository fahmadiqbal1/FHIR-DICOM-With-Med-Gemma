"""Database models for FHIR-DICOM system"""

from sqlalchemy import create_engine, Column, String, DateTime, Text, Integer, Boolean, LargeBinary, ForeignKey
from sqlalchemy.ext.declarative import declarative_base
from sqlalchemy.orm import sessionmaker, relationship
from sqlalchemy.dialects.postgresql import UUID, JSONB
from datetime import datetime
import uuid

Base = declarative_base()

class Patient(Base):
    """FHIR Patient resource"""
    __tablename__ = 'patients'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    fhir_id = Column(String(255), unique=True, nullable=False)
    family_name = Column(String(255))
    given_name = Column(String(255))
    birth_date = Column(DateTime)
    gender = Column(String(50))
    identifier = Column(JSONB)
    fhir_resource = Column(JSONB)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    imaging_studies = relationship("ImagingStudy", back_populates="patient")
    diagnostic_reports = relationship("DiagnosticReport", back_populates="patient")

class ImagingStudy(Base):
    """FHIR ImagingStudy resource"""
    __tablename__ = 'imaging_studies'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    fhir_id = Column(String(255), unique=True, nullable=False)
    patient_id = Column(UUID(as_uuid=True), ForeignKey('patients.id'), nullable=False)
    study_instance_uid = Column(String(255), unique=True, nullable=False)
    accession_number = Column(String(255))
    modality = Column(String(50))
    description = Column(Text)
    started = Column(DateTime)
    fhir_resource = Column(JSONB)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    patient = relationship("Patient", back_populates="imaging_studies")
    dicom_studies = relationship("DicomStudy", back_populates="imaging_study")

class DicomStudy(Base):
    """DICOM Study metadata"""
    __tablename__ = 'dicom_studies'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    imaging_study_id = Column(UUID(as_uuid=True), ForeignKey('imaging_studies.id'), nullable=False)
    study_instance_uid = Column(String(255), unique=True, nullable=False)
    study_date = Column(DateTime)
    study_time = Column(String(50))
    study_description = Column(Text)
    modality = Column(String(50))
    institution_name = Column(String(255))
    file_path = Column(String(500))
    file_size = Column(Integer)
    dicom_metadata = Column(JSONB)
    processed = Column(Boolean, default=False)
    created_at = Column(DateTime, default=datetime.utcnow)
    
    # Relationships
    imaging_study = relationship("ImagingStudy", back_populates="dicom_studies")
    ai_analyses = relationship("AIAnalysis", back_populates="dicom_study")

class DiagnosticReport(Base):
    """FHIR DiagnosticReport resource"""
    __tablename__ = 'diagnostic_reports'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    fhir_id = Column(String(255), unique=True, nullable=False)
    patient_id = Column(UUID(as_uuid=True), ForeignKey('patients.id'), nullable=False)
    status = Column(String(50))
    category = Column(String(100))
    code = Column(String(100))
    effective_datetime = Column(DateTime)
    conclusion = Column(Text)
    fhir_resource = Column(JSONB)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)
    
    # Relationships
    patient = relationship("Patient", back_populates="diagnostic_reports")

class AIAnalysis(Base):
    """Med-Gemma AI analysis results"""
    __tablename__ = 'ai_analyses'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    dicom_study_id = Column(UUID(as_uuid=True), ForeignKey('dicom_studies.id'), nullable=False)
    model_name = Column(String(255), nullable=False)
    model_version = Column(String(100))
    analysis_type = Column(String(100))
    input_text = Column(Text)
    output_text = Column(Text)
    confidence_score = Column(String(50))
    processing_time = Column(Integer)  # in milliseconds
    status = Column(String(50))
    error_message = Column(Text)
    created_at = Column(DateTime, default=datetime.utcnow)
    
    # Relationships
    dicom_study = relationship("DicomStudy", back_populates="ai_analyses")

class AuditLog(Base):
    """Audit log for tracking system activities"""
    __tablename__ = 'audit_logs'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    resource_type = Column(String(100))
    resource_id = Column(String(255))
    action = Column(String(100))
    user_id = Column(String(255))
    timestamp = Column(DateTime, default=datetime.utcnow)
    details = Column(JSONB)
    ip_address = Column(String(50))
    user_agent = Column(String(500))

class SystemConfiguration(Base):
    """System configuration settings"""
    __tablename__ = 'system_configurations'
    
    id = Column(UUID(as_uuid=True), primary_key=True, default=uuid.uuid4)
    key = Column(String(255), unique=True, nullable=False)
    value = Column(Text)
    description = Column(Text)
    is_encrypted = Column(Boolean, default=False)
    created_at = Column(DateTime, default=datetime.utcnow)
    updated_at = Column(DateTime, default=datetime.utcnow, onupdate=datetime.utcnow)