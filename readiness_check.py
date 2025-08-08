#!/usr/bin/env python3
"""
FHIR-DICOM with Med-Gemma Readiness Check Script

This script performs comprehensive checks to determine if the FHIR-DICOM system
with Med-Gemma integration is completed and ready for launch.
"""

import os
import sys
import json
import subprocess
import importlib.util
from pathlib import Path
from typing import Dict, List, Tuple, Any
import logging

# Configure logging
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')
logger = logging.getLogger(__name__)

class ReadinessChecker:
    """Main class for performing readiness checks on the FHIR-DICOM system."""
    
    def __init__(self, project_root: str = None):
        self.project_root = Path(project_root) if project_root else Path.cwd()
        self.results = {}
        self.overall_ready = True
        
    def run_all_checks(self) -> Dict[str, Any]:
        """Run all readiness checks and return comprehensive results."""
        logger.info("Starting FHIR-DICOM with Med-Gemma readiness checks...")
        
        checks = [
            ("Project Structure", self._check_project_structure),
            ("FHIR Components", self._check_fhir_components),
            ("DICOM Components", self._check_dicom_components),
            ("Med-Gemma Integration", self._check_medgemma_integration),
            ("Dependencies", self._check_dependencies),
            ("Configuration", self._check_configuration),
            ("Tests", self._check_tests),
            ("Documentation", self._check_documentation),
            ("Security", self._check_security),
            ("Deployment Readiness", self._check_deployment_readiness)
        ]
        
        for check_name, check_function in checks:
            logger.info(f"Running {check_name} checks...")
            try:
                result = check_function()
                self.results[check_name] = result
                if not result.get('passed', False):
                    self.overall_ready = False
            except Exception as e:
                logger.error(f"Error in {check_name} check: {str(e)}")
                self.results[check_name] = {
                    'passed': False,
                    'error': str(e),
                    'issues': [f"Check failed with error: {str(e)}"]
                }
                self.overall_ready = False
        
        return self._generate_final_report()
    
    def _check_project_structure(self) -> Dict[str, Any]:
        """Check if the project has the expected directory structure."""
        required_dirs = [
            'src/fhir',
            'src/dicom', 
            'src/medgemma',
            'tests',
            'docs',
            'config'
        ]
        
        optional_dirs = [
            'scripts',
            'data',
            'models',
            'api'
        ]
        
        issues = []
        warnings = []
        
        for dir_path in required_dirs:
            full_path = self.project_root / dir_path
            if not full_path.exists():
                issues.append(f"Missing required directory: {dir_path}")
        
        for dir_path in optional_dirs:
            full_path = self.project_root / dir_path
            if not full_path.exists():
                warnings.append(f"Recommended directory not found: {dir_path}")
        
        # Check for essential files
        essential_files = [
            'requirements.txt',
            'setup.py',
            'README.md',
            'config/app.yaml'
        ]
        
        for file_path in essential_files:
            full_path = self.project_root / file_path
            if not full_path.exists():
                issues.append(f"Missing essential file: {file_path}")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'warnings': warnings,
            'score': max(0, 100 - len(issues) * 10 - len(warnings) * 2)
        }
    
    def _check_fhir_components(self) -> Dict[str, Any]:
        """Check FHIR server and resource handling components."""
        issues = []
        components_found = []
        
        fhir_files = [
            'src/fhir/server.py',
            'src/fhir/resources.py',
            'src/fhir/validator.py',
            'src/fhir/api.py'
        ]
        
        for file_path in fhir_files:
            full_path = self.project_root / file_path
            if full_path.exists():
                components_found.append(file_path)
            else:
                issues.append(f"Missing FHIR component: {file_path}")
        
        # Check for FHIR dependencies
        requirements_file = self.project_root / 'requirements.txt'
        fhir_deps = ['fhir.resources', 'fhirclient', 'pyFHIR']
        missing_deps = []
        
        if requirements_file.exists():
            content = requirements_file.read_text()
            for dep in fhir_deps:
                if dep not in content:
                    missing_deps.append(dep)
        else:
            missing_deps = fhir_deps
        
        if missing_deps:
            issues.append(f"Missing FHIR dependencies: {', '.join(missing_deps)}")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'components_found': components_found,
            'score': max(0, 100 - len(issues) * 15)
        }
    
    def _check_dicom_components(self) -> Dict[str, Any]:
        """Check DICOM processing and conversion components."""
        issues = []
        components_found = []
        
        dicom_files = [
            'src/dicom/parser.py',
            'src/dicom/converter.py',
            'src/dicom/storage.py',
            'src/dicom/validator.py'
        ]
        
        for file_path in dicom_files:
            full_path = self.project_root / file_path
            if full_path.exists():
                components_found.append(file_path)
            else:
                issues.append(f"Missing DICOM component: {file_path}")
        
        # Check for DICOM dependencies
        requirements_file = self.project_root / 'requirements.txt'
        dicom_deps = ['pydicom', 'dicom2nifti', 'dcmstack']
        missing_deps = []
        
        if requirements_file.exists():
            content = requirements_file.read_text()
            for dep in dicom_deps:
                if dep not in content:
                    missing_deps.append(dep)
        else:
            missing_deps = dicom_deps
        
        if missing_deps:
            issues.append(f"Missing DICOM dependencies: {', '.join(missing_deps)}")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'components_found': components_found,
            'score': max(0, 100 - len(issues) * 15)
        }
    
    def _check_medgemma_integration(self) -> Dict[str, Any]:
        """Check Med-Gemma AI integration components."""
        issues = []
        components_found = []
        
        medgemma_files = [
            'src/medgemma/model.py',
            'src/medgemma/inference.py',
            'src/medgemma/preprocessing.py',
            'src/medgemma/api.py'
        ]
        
        for file_path in medgemma_files:
            full_path = self.project_root / file_path
            if full_path.exists():
                components_found.append(file_path)
            else:
                issues.append(f"Missing Med-Gemma component: {file_path}")
        
        # Check for AI/ML dependencies
        requirements_file = self.project_root / 'requirements.txt'
        ml_deps = ['transformers', 'torch', 'tensorflow', 'huggingface-hub']
        missing_deps = []
        
        if requirements_file.exists():
            content = requirements_file.read_text()
            for dep in ml_deps:
                if dep not in content:
                    missing_deps.append(dep)
        else:
            missing_deps = ml_deps
        
        if missing_deps:
            issues.append(f"Missing ML dependencies: {', '.join(missing_deps)}")
        
        # Check for model files
        model_dir = self.project_root / 'models'
        if not model_dir.exists():
            issues.append("Missing models directory")
        elif not any(model_dir.iterdir()):
            issues.append("Models directory is empty")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'components_found': components_found,
            'score': max(0, 100 - len(issues) * 15)
        }
    
    def _check_dependencies(self) -> Dict[str, Any]:
        """Check if all required dependencies are properly defined and installable."""
        issues = []
        
        requirements_file = self.project_root / 'requirements.txt'
        setup_file = self.project_root / 'setup.py'
        
        if not requirements_file.exists() and not setup_file.exists():
            issues.append("No dependency management file found (requirements.txt or setup.py)")
            return {'passed': False, 'issues': issues, 'score': 0}
        
        # Test pip install dry run if requirements.txt exists
        if requirements_file.exists():
            try:
                result = subprocess.run(
                    ['pip', 'install', '--dry-run', '-r', str(requirements_file)],
                    capture_output=True, text=True, timeout=30
                )
                if result.returncode != 0:
                    issues.append(f"Dependency installation would fail: {result.stderr[:200]}")
            except subprocess.TimeoutExpired:
                issues.append("Dependency check timed out")
            except Exception as e:
                issues.append(f"Could not verify dependencies: {str(e)}")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'score': max(0, 100 - len(issues) * 20)
        }
    
    def _check_configuration(self) -> Dict[str, Any]:
        """Check configuration files and environment setup."""
        issues = []
        config_files_found = []
        
        expected_configs = [
            'config/app.yaml',
            'config/database.yaml',
            'config/logging.yaml',
            '.env.example'
        ]
        
        for config_file in expected_configs:
            full_path = self.project_root / config_file
            if full_path.exists():
                config_files_found.append(config_file)
            else:
                issues.append(f"Missing configuration file: {config_file}")
        
        # Check for Docker configuration
        docker_files = ['Dockerfile', 'docker-compose.yml']
        docker_found = any((self.project_root / f).exists() for f in docker_files)
        
        if not docker_found:
            issues.append("No Docker configuration found (Dockerfile or docker-compose.yml)")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'config_files_found': config_files_found,
            'docker_configured': docker_found,
            'score': max(0, 100 - len(issues) * 12)
        }
    
    def _check_tests(self) -> Dict[str, Any]:
        """Check test coverage and test infrastructure."""
        issues = []
        test_files = []
        
        test_dirs = ['tests', 'test']
        test_dir_found = False
        
        for test_dir in test_dirs:
            test_path = self.project_root / test_dir
            if test_path.exists():
                test_dir_found = True
                # Find test files
                for test_file in test_path.rglob('test_*.py'):
                    test_files.append(str(test_file.relative_to(self.project_root)))
                break
        
        if not test_dir_found:
            issues.append("No test directory found")
        elif not test_files:
            issues.append("No test files found in test directory")
        
        # Check for test configuration
        test_configs = ['pytest.ini', 'setup.cfg', 'pyproject.toml']
        test_config_found = any((self.project_root / f).exists() for f in test_configs)
        
        if not test_config_found:
            issues.append("No test configuration found (pytest.ini, setup.cfg, or pyproject.toml)")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'test_files': test_files,
            'test_config_found': test_config_found,
            'score': max(0, 100 - len(issues) * 25)
        }
    
    def _check_documentation(self) -> Dict[str, Any]:
        """Check documentation completeness."""
        issues = []
        docs_found = []
        
        essential_docs = [
            'README.md',
            'docs/installation.md',
            'docs/api.md',
            'docs/deployment.md'
        ]
        
        for doc_file in essential_docs:
            full_path = self.project_root / doc_file
            if full_path.exists():
                docs_found.append(doc_file)
            else:
                issues.append(f"Missing documentation: {doc_file}")
        
        # Check README quality
        readme_path = self.project_root / 'README.md'
        if readme_path.exists():
            content = readme_path.read_text()
            if len(content) < 500:
                issues.append("README.md is too brief (less than 500 characters)")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'docs_found': docs_found,
            'score': max(0, 100 - len(issues) * 15)
        }
    
    def _check_security(self) -> Dict[str, Any]:
        """Check security configurations and best practices."""
        issues = []
        
        # Check for security-related files
        security_files = [
            '.env.example',
            'config/security.yaml',
            'requirements-security.txt'
        ]
        
        security_files_found = []
        for sec_file in security_files:
            if (self.project_root / sec_file).exists():
                security_files_found.append(sec_file)
        
        if not security_files_found:
            issues.append("No security configuration files found")
        
        # Check for secrets in code (basic check)
        for py_file in self.project_root.rglob('*.py'):
            try:
                content = py_file.read_text()
                if 'password' in content.lower() or 'secret' in content.lower():
                    if 'password=' in content or 'secret=' in content:
                        issues.append(f"Potential hardcoded secrets in {py_file.relative_to(self.project_root)}")
            except Exception:
                continue
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'security_files_found': security_files_found,
            'score': max(0, 100 - len(issues) * 20)
        }
    
    def _check_deployment_readiness(self) -> Dict[str, Any]:
        """Check deployment readiness and production configurations."""
        issues = []
        
        # Check for production configurations
        prod_configs = [
            'Dockerfile',
            'docker-compose.yml',
            'kubernetes/',
            'helm/',
            'config/production.yaml'
        ]
        
        prod_configs_found = []
        for config in prod_configs:
            if (self.project_root / config).exists():
                prod_configs_found.append(config)
        
        if not prod_configs_found:
            issues.append("No production deployment configurations found")
        
        # Check for health check endpoints
        health_files = [
            'src/health.py',
            'src/api/health.py',
            'health_check.py'
        ]
        
        health_check_found = any((self.project_root / f).exists() for f in health_files)
        if not health_check_found:
            issues.append("No health check endpoint implementation found")
        
        return {
            'passed': len(issues) == 0,
            'issues': issues,
            'prod_configs_found': prod_configs_found,
            'health_check_found': health_check_found,
            'score': max(0, 100 - len(issues) * 25)
        }
    
    def _generate_final_report(self) -> Dict[str, Any]:
        """Generate comprehensive readiness report."""
        total_score = sum(result.get('score', 0) for result in self.results.values())
        max_score = len(self.results) * 100
        overall_score = (total_score / max_score * 100) if max_score > 0 else 0
        
        # Determine readiness level
        if overall_score >= 90:
            readiness_level = "Production Ready"
        elif overall_score >= 75:
            readiness_level = "Nearly Ready"
        elif overall_score >= 50:
            readiness_level = "Development Stage"
        else:
            readiness_level = "Early Development"
        
        return {
            'overall_ready': self.overall_ready and overall_score >= 75,
            'readiness_level': readiness_level,
            'overall_score': round(overall_score, 1),
            'total_issues': sum(len(result.get('issues', [])) for result in self.results.values()),
            'check_results': self.results,
            'summary': self._generate_summary()
        }
    
    def _generate_summary(self) -> Dict[str, List[str]]:
        """Generate a summary of critical issues and recommendations."""
        critical_issues = []
        recommendations = []
        
        for check_name, result in self.results.items():
            if not result.get('passed', False):
                issues = result.get('issues', [])
                if issues:
                    critical_issues.extend([f"[{check_name}] {issue}" for issue in issues[:2]])
        
        # Generate recommendations based on findings
        if not self.overall_ready:
            recommendations.append("Complete missing components before production deployment")
            recommendations.append("Implement comprehensive testing suite")
            recommendations.append("Add proper documentation and deployment configurations")
        
        return {
            'critical_issues': critical_issues[:10],  # Top 10 critical issues
            'recommendations': recommendations
        }


def main():
    """Main function to run readiness checks."""
    checker = ReadinessChecker()
    results = checker.run_all_checks()
    
    # Print results
    print("\n" + "="*60)
    print("FHIR-DICOM with Med-Gemma Readiness Check Results")
    print("="*60)
    
    print(f"\nOverall Readiness: {results['readiness_level']}")
    print(f"Overall Score: {results['overall_score']}/100")
    print(f"System Ready for Launch: {'YES' if results['overall_ready'] else 'NO'}")
    print(f"Total Issues Found: {results['total_issues']}")
    
    # Print check details
    print(f"\nDetailed Results:")
    print("-" * 40)
    
    for check_name, result in results['check_results'].items():
        status = "PASS" if result.get('passed', False) else "FAIL"
        score = result.get('score', 0)
        print(f"{check_name:25} [{status:4}] Score: {score:3}/100")
        
        if result.get('issues'):
            for issue in result['issues'][:3]:  # Show top 3 issues
                print(f"  ⚠️  {issue}")
    
    # Print summary
    if results['summary']['critical_issues']:
        print(f"\nCritical Issues:")
        print("-" * 20)
        for issue in results['summary']['critical_issues']:
            print(f"❌ {issue}")
    
    if results['summary']['recommendations']:
        print(f"\nRecommendations:")
        print("-" * 20)
        for rec in results['summary']['recommendations']:
            print(f"💡 {rec}")
    
    # Save results to file
    results_file = Path('readiness_check_results.json')
    with open(results_file, 'w') as f:
        json.dump(results, f, indent=2)
    
    print(f"\nDetailed results saved to: {results_file}")
    
    # Exit with appropriate code
    sys.exit(0 if results['overall_ready'] else 1)


if __name__ == "__main__":
    main()