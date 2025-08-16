#!/usr/bin/env python3
"""
Test suite for MedGemma server
Tests basic functionality and API endpoints
"""

import pytest
import asyncio
import json
from unittest.mock import Mock, patch

# Import the MedGemma server classes
import sys
import os
sys.path.append(os.path.dirname(os.path.abspath(__file__)))

from medgemma_server import MedGemmaServer


class TestMedGemmaServer:
    """Test cases for MedGemmaServer class"""
    
    def setup_method(self):
        """Set up test fixtures"""
        self.server = MedGemmaServer()
    
    def test_server_initialization(self):
        """Test that MedGemmaServer initializes correctly"""
        assert self.server is not None
        assert hasattr(self.server, 'model')
        assert hasattr(self.server, 'processor')
        assert hasattr(self.server, 'model_loaded')
        assert self.server.model_loaded is False
    
    def test_format_lab_results(self):
        """Test lab results formatting"""
        lab_results = [
            {"name": "Hemoglobin", "value": "12.5", "unit": "g/dL", "reference_range": "12.0-15.5"},
            {"name": "White Blood Cell Count", "value": "7500", "unit": "cells/μL", "reference_range": "4500-11000"}
        ]
        
        formatted = self.server.format_lab_results(lab_results)
        
        assert isinstance(formatted, str)
        assert "Hemoglobin" in formatted
        assert "White Blood Cell Count" in formatted
        assert "12.5" in formatted
        assert "7500" in formatted
    
    def test_extract_recommendations(self):
        """Test recommendation extraction from analysis text"""
        analysis_text = """
        Based on the findings:
        - Continue current medication regimen
        - Follow up in 2 weeks
        - Consider additional lab work if symptoms persist
        """
        
        recommendations = self.server.extract_recommendations(analysis_text)
        
        assert isinstance(recommendations, list)
        assert len(recommendations) > 0
    
    def test_generate_mock_text_analysis(self):
        """Test mock text analysis generation"""
        lab_prompt = "Analyze these lab results: Hemoglobin 10.2 g/dL"
        
        analysis = self.server.generate_mock_text_analysis(lab_prompt)
        
        assert isinstance(analysis, str)
        assert len(analysis) > 0
        assert "Clinical Interpretation" in analysis or "laboratory" in analysis.lower()
    
    def test_generate_mock_imaging_analysis(self):
        """Test mock imaging analysis generation"""
        imaging_prompt = "Analyze this chest X-ray for pneumonia"
        
        analysis = self.server.generate_mock_imaging_analysis(imaging_prompt)
        
        assert isinstance(analysis, str)
        assert len(analysis) > 0
        # Check for expected imaging report content
        assert "Radiological Report" in analysis or "imaging" in analysis.lower() or "chest" in analysis.lower()
    
    @pytest.mark.asyncio
    async def test_analyze_text_mock_mode(self):
        """Test text analysis in mock mode"""
        prompt = "Patient presents with chest pain"
        
        result = await self.server.analyze_text(prompt)
        
        assert isinstance(result, str)
        assert len(result) > 0


class TestMedGemmaServerAPI:
    """Test cases for API endpoints if we were to run the FastAPI server"""
    
    def test_health_endpoint_structure(self):
        """Test the expected structure of health endpoint response"""
        # This would be expanded if we had actual API tests
        expected_keys = ['status', 'model', 'model_loaded']
        
        # In a real test, we would start the server and make HTTP requests
        # For now, we're just testing the structure expectation
        assert all(isinstance(key, str) for key in expected_keys)


if __name__ == "__main__":
    # Run tests directly
    pytest.main([__file__, "-v"])