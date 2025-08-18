# Lab Equipment Integration System

## Overview

This system provides automated result fetching from lab equipment with OCR backup capabilities, designed to minimize human error and maximize accuracy in lab result processing.

## Supported Equipment

### 1. Mission HA-360 3-diff Automatic Hematology Analyser

- **Manufacturer**: Mission Bio
- **Connection Types**: TCP/IP, Serial, File Transfer
- **Protocol**: ASTM
- **Supported Tests**: WBC, RBC, HGB, HCT, PLT, MCV, MCH, MCHC, RDW, MPV, Differential counts
- **Configuration**:

  ```json
  {
    "baud_rate": 9600,
    "data_bits": 8,
    "stop_bits": 1,
    "parity": "none",
    "watch_directory": "/var/lab/mission",
    "file_pattern": "*.dat"
  }
  ```

### 2. CBS-40 Electrolyte Analyser

- **Manufacturer**: Caretium Medical Instruments
- **Connection Types**: TCP/IP, File Transfer
- **Protocol**: LIS (Laboratory Information System)
- **Supported Tests**: Na+, K+, Cl-, CO2, BUN, Creatinine, Glucose, Calcium, Magnesium
- **Configuration**:

  ```json
  {
    "calibration_frequency": "daily",
    "qc_frequency": "every_batch",
    "watch_directory": "/var/lab/cbs40",
    "file_pattern": "*.csv"
  }
  ```

### 3. Contec BC300 Semi-auto Bio Chemistry Analyser

- **Manufacturer**: Contec Medical Systems
- **Connection Types**: Serial, File Transfer
- **Protocol**: Custom CSV Format
- **Supported Tests**: ALT, AST, ALP, GGT, Bilirubin, Cholesterol, HDL, LDL, Triglycerides, etc.
- **Configuration**:

  ```json
  {
    "serial_port": "/dev/ttyUSB0",
    "baud_rate": 9600,
    "watch_directory": "/var/lab/contec",
    "file_pattern": "*.csv",
    "header_rows": 2
  }
  ```

## Integration Methods

### 1. Automatic Equipment Integration

- **Real-time Connection**: TCP/IP or Serial communication
- **File Monitoring**: Watch directories for new result files
- **Protocol Support**: ASTM, HL7, LIS, Custom formats
- **Automatic Processing**: Results are automatically parsed and stored

### 2. OCR Backup System

- **Image Capture**: Take photos of result displays/printouts
- **OCR Processing**: Extract text using multiple OCR engines
- **Confidence Scoring**: Results below 80% confidence require verification
- **Manual Verification**: Lab techs can verify and correct OCR results

## Features

### Equipment Management

- **Real-time Status Monitoring**: Track online/offline status
- **Connection Testing**: Verify equipment connectivity
- **Configuration Management**: Store equipment-specific settings
- **Supported Test Mapping**: Define which tests each equipment can perform

### Result Processing

- **Automatic Quality Control**: Validate results against reference ranges
- **Multi-source Support**: Handle results from equipment, OCR, or manual entry
- **Status Tracking**: Preliminary, Final, Needs Verification, Corrected
- **Audit Trail**: Complete tracking of result source and modifications

### OCR Capabilities

- **Multiple OCR Engines**: OCR.space API and Tesseract fallback
- **Smart Parsing**: Recognize common lab result formats
- **Test Pattern Recognition**: Identify Hematology, Electrolyte, and Biochemistry results
- **Reference Range Extraction**: Automatically detect normal ranges

## API Endpoints

### Equipment Management APIs

```http
GET    /api/lab-equipment/              - List all equipment
GET    /api/lab-equipment/{id}          - Get equipment details
POST   /api/lab-equipment/fetch-results - Fetch results from equipment
POST   /api/lab-equipment/{id}/test-connection - Test equipment connection
```

### Result Management APIs

```http
GET    /api/lab-equipment/results       - List lab results
PUT    /api/lab-equipment/results/{id}/verify - Verify a result
POST   /api/lab-equipment/upload-result-image - Upload OCR image
GET    /api/lab-equipment/statistics    - Get equipment statistics
```

## Installation & Setup

### 1. Database Migration

```bash
php artisan migrate
php artisan db:seed --class=LabEquipmentSeeder
```

### 2. Environment Configuration

```env
# OCR Service Configuration
OCR_API_KEY=your_ocr_space_api_key
OCR_ENDPOINT=https://api.ocr.space/parse/image

# Equipment Integration
LAB_RESULTS_WATCH_DIR=/var/lab
LAB_RESULTS_ARCHIVE_DIR=/var/lab/processed
```

### 3. Directory Setup

```bash
sudo mkdir -p /var/lab/{mission,cbs40,contec,processed}
sudo chown -R www-data:www-data /var/lab
sudo chmod -R 755 /var/lab
```

### 4. Automated Fetching

```bash
# Add to crontab for automatic result fetching every 5 minutes
*/5 * * * * cd /path/to/project && php artisan lab:fetch-results

# Manual fetch from specific equipment
php artisan lab:fetch-results --equipment=1
```

## Equipment Configuration Examples

### TCP/IP Connection Setup

```json
{
  "ip_address": "192.168.1.100",
  "port": 4001,
  "connection_type": "tcp",
  "protocol": "astm",
  "timeout": 30,
  "retry_attempts": 3
}
```

### File Transfer Setup

```json
{
  "watch_directory": "/var/lab/mission",
  "file_pattern": "*.dat",
  "connection_type": "file_transfer",
  "auto_archive": true,
  "poll_interval": 60
}
```

### Serial Connection Setup

```json
{
  "serial_port": "/dev/ttyUSB0",
  "baud_rate": 9600,
  "data_bits": 8,
  "stop_bits": 1,
  "parity": "none",
  "connection_type": "serial"
}
```

## OCR Configuration

### OCR.space API Setup

1. Register at <https://ocr.space/OCRAPI>
2. Get your API key
3. Add to environment: `OCR_API_KEY=your_key_here`

### Tesseract Installation (Backup)

```bash
# Ubuntu/Debian
sudo apt-get install tesseract-ocr

# macOS
brew install tesseract

# Verify installation
tesseract --version
```

## Result Parsing Patterns

### Hematology Results (Mission HA-360)

```text
WBC: 7.5 K/uL (4.0-10.0)
RBC: 4.2 M/uL (4.2-5.4)
HGB: 13.5 g/dL (12.0-15.5)
HCT: 40.2 % (37.0-47.0)
PLT: 250 K/uL (150-450)
```

### Electrolyte Results (CBS-40)

```text
Na+: 140 mmol/L (136-145)
K+: 4.0 mmol/L (3.5-5.1)
Cl-: 102 mmol/L (98-107)
CO2: 24 mmol/L (22-29)
```

### Biochemistry Results (Contec BC300)

```text
ALT: 25 U/L (7-56)
AST: 30 U/L (10-40)
Glucose: 95 mg/dL (70-100)
Cholesterol: 180 mg/dL (<200)
```

## Troubleshooting

### Connection Issues

1. **Check Network Connectivity**: Verify IP address and port
2. **Firewall Settings**: Ensure ports are open
3. **Equipment Status**: Verify equipment is powered on and ready
4. **Cable Connections**: Check serial/network cables

### OCR Issues

1. **Image Quality**: Ensure clear, well-lit images
2. **API Limits**: Check OCR.space API usage limits
3. **Tesseract Installation**: Verify backup OCR engine is installed
4. **File Permissions**: Check image upload permissions

### Result Processing Issues

1. **Test Mapping**: Verify equipment supports the ordered tests
2. **Sample ID Matching**: Ensure sample IDs match lab orders
3. **Reference Ranges**: Check test reference range configurations
4. **Quality Control**: Review QC settings for each equipment

## Security Considerations

### Network Security

- Use VPN for equipment network access
- Implement firewall rules for equipment IPs
- Regular security updates for equipment firmware

### Data Security

- Encrypt result data in transit and at rest
- Implement access controls for equipment configuration
- Audit all result modifications and access
- Regular backup of result data

### Compliance

- HIPAA compliance for patient data
- Lab quality standards (CLIA, CAP)
- Equipment calibration and maintenance logs
- Regular validation of automated processes

## Monitoring & Maintenance

### Equipment Monitoring

- Real-time status dashboards
- Automatic alerts for offline equipment
- Connection failure notifications
- Result volume monitoring

### Quality Assurance

- Daily equipment connection tests
- OCR confidence score monitoring
- Result verification rate tracking
- Equipment performance analytics

### Maintenance Schedule

- Daily: Equipment status check, result review
- Weekly: Equipment calibration verification
- Monthly: System performance review
- Quarterly: Equipment maintenance, software updates
