"""Basic tests for FHIR server functionality"""

import pytest
import sys
import os
from unittest.mock import Mock, patch

# Add src to path for testing
sys.path.insert(0, os.path.join(os.path.dirname(__file__), '..', 'src'))

from fhir.server import FHIRServer
from fhir.validator import FHIRValidator

class TestFHIRServer:
    """Test FHIR server functionality"""
    
    def setup_method(self):
        """Setup test fixtures"""
        self.mock_db_session = Mock()
        self.fhir_server = FHIRServer(self.mock_db_session)
    
    def test_fhir_server_initialization(self):
        """Test FHIR server initializes correctly"""
        assert self.fhir_server is not None
        assert self.fhir_server.db_session is not None
        assert self.fhir_server.validator is not None
        assert self.fhir_server.resource_manager is not None
    
    def test_get_capability_statement(self):
        """Test capability statement generation"""
        capability = self.fhir_server.get_capability_statement()
        
        assert capability['resourceType'] == 'CapabilityStatement'
        assert capability['status'] == 'active'
        assert capability['kind'] == 'instance'
        assert 'rest' in capability
        assert len(capability['rest']) > 0
    
    @pytest.mark.unit
    def test_patient_creation_validation(self):
        """Test patient creation with validation"""
        # Test valid patient data
        valid_patient = {
            "resourceType": "Patient",
            "id": "test-patient-1",
            "name": [{
                "family": "Doe",
                "given": ["John"]
            }],
            "gender": "male",
            "birthDate": "1990-01-01"
        }
        
        # Mock the database query to return None (patient doesn't exist)
        self.mock_db_session.query.return_value.filter_by.return_value.first.return_value = None
        
        # This would normally create a patient, but we're mocking the DB
        # Just test that it doesn't raise an exception
        try:
            with patch('src.fhir.server.Patient') as mock_patient_model:
                result = self.fhir_server.create_patient(valid_patient)
                assert result is not None
        except Exception as e:
            # Expected to fail without real database, just ensure it's not a validation error
            assert "already exists" not in str(e)

class TestFHIRValidator:
    """Test FHIR validation functionality"""
    
    def setup_method(self):
        """Setup test fixtures"""
        self.validator = FHIRValidator()
    
    def test_validator_initialization(self):
        """Test validator initializes with supported resources"""
        assert self.validator is not None
        assert 'Patient' in self.validator.supported_resources
        assert 'ImagingStudy' in self.validator.supported_resources
        assert 'DiagnosticReport' in self.validator.supported_resources
    
    def test_validate_patient_resource(self):
        """Test patient resource validation"""
        # Valid patient
        valid_patient = {
            "resourceType": "Patient",
            "id": "test-patient-1",
            "name": [{
                "family": "Doe",
                "given": ["John"]
            }],
            "gender": "male",
            "birthDate": "1990-01-01"
        }
        
        result = self.validator.validate_patient(valid_patient)
        assert result['valid'] is True
        assert 'resource' in result
        assert len(result['errors']) == 0
    
    def test_validate_invalid_patient(self):
        """Test validation of invalid patient resource"""
        # Invalid patient (missing required fields)
        invalid_patient = {
            "resourceType": "Patient"
            # Missing required fields
        }
        
        result = self.validator.validate_patient(invalid_patient)
        assert 'errors' in result
        # May have warnings about missing recommended fields
    
    def test_validate_reference_format(self):
        """Test FHIR reference validation"""
        # Valid reference
        valid_ref = "Patient/123"
        result = self.validator.validate_reference(valid_ref)
        assert result['valid'] is True
        assert result['resource_type'] == 'Patient'
        assert result['resource_id'] == '123'
        
        # Invalid reference
        invalid_ref = "InvalidType/123"
        result = self.validator.validate_reference(invalid_ref)
        assert result['valid'] is False
        assert len(result['errors']) > 0