from setuptools import setup, find_packages

setup(
    name="fhir-dicom-medgemma",
    version="1.0.0",
    description="FHIR-DICOM integration with Med-Gemma AI model",
    author="Med-Gemma Team",
    author_email="contact@medgemma.ai",
    packages=find_packages(where="src"),
    package_dir={"": "src"},
    python_requires=">=3.8",
    install_requires=[
        line.strip() 
        for line in open("requirements.txt").readlines() 
        if line.strip() and not line.startswith("#")
    ],
    classifiers=[
        "Development Status :: 4 - Beta",
        "Intended Audience :: Healthcare Industry",
        "License :: OSI Approved :: MIT License",
        "Programming Language :: Python :: 3.8",
        "Programming Language :: Python :: 3.9",
        "Programming Language :: Python :: 3.10",
        "Programming Language :: Python :: 3.11",
    ],
    entry_points={
        "console_scripts": [
            "fhir-dicom=src.main:main",
        ],
    },
)