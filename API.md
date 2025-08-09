# API Documentation

## Base URL
- Development: `http://localhost:8000/api`
- Production: `https://yourdomain.com/api`

## Authentication
Currently, the API endpoints are public for demo purposes. In production, implement proper authentication (JWT tokens, API keys, etc.).

## Endpoints

### Integration Status

#### Get MedGemma Integration Status
```http
GET /integrations/medgemma
```

**Response:**
```json
{
  "name": "MedGemma",
  "integrated": true,
  "enabled": false,
  "configured": false,
  "model": "medgemma"
}
```

### Patient Data

#### List Patients
```http
GET /reports/patients
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "uuid": "b4401155-9a5f-4caa-9fc2-741bedc2f7f5",
      "mrn": "MRN9942993",
      "first_name": "Abbey",
      "last_name": "Schuster",
      "name": "Abbey Schuster",
      "dob": "2020-01-06",
      "sex": "female",
      "counts": {
        "imaging_studies": 1,
        "lab_orders": 1,
        "prescriptions": 2,
        "clinical_notes": 3
      }
    }
  ]
}
```

#### Get Patient Details
```http
GET /reports/patients/{patient_id}
```

**Response:**
```json
{
  "id": 1,
  "uuid": "b4401155-9a5f-4caa-9fc2-741bedc2f7f5",
  "mrn": "MRN9942993",
  "first_name": "Abbey",
  "last_name": "Schuster",
  "name": "Abbey Schuster",
  "dob": "2020-01-06",
  "sex": "female",
  "imaging_studies": [
    {
      "id": 1,
      "uuid": "659f0151-cf49-4087-82a3-3ec28144a3a4",
      "description": "Chest X-ray",
      "modality": "XR",
      "started_at": "2025-07-26 06:03:00",
      "status": "completed",
      "images_count": 2,
      "ai_results": [
        {
          "id": 1,
          "model": "medgemma",
          "status": "completed",
          "confidence_score": 0.82,
          "result": {
            "study_uuid": "659f0151-cf49-4087-82a3-3ec28144a3a4",
            "modality": "XR",
            "findings": ["No acute cardiopulmonary findings"],
            "impression": "Normal chest radiograph",
            "recommendations": ["Symptomatic management and follow-up if symptoms persist"]
          },
          "created_at": "2025-08-09 05:10:09"
        }
      ]
    }
  ],
  "lab_orders": [
    {
      "id": 1,
      "code": "WBC",
      "name": "White Blood Cells",
      "status": "resulted",
      "priority": "routine",
      "result_value": "9.52",
      "result_flag": "normal",
      "result_notes": "Auto-generated demo result"
    }
  ],
  "prescriptions": [
    {
      "id": 1,
      "medication": "Acetaminophen",
      "strength": "500mg",
      "dosage": "500mg",
      "frequency": "every 6 hours",
      "route": "oral",
      "quantity": "30",
      "status": "active",
      "notes": null
    }
  ],
  "clinical_notes": [
    {
      "id": 1,
      "soap_subjective": "Patient reports fatigue",
      "soap_objective": "Vital signs stable",
      "soap_assessment": "Likely viral syndrome",
      "soap_plan": "Rest, hydration, symptomatic treatment",
      "created_at": "2025-08-09 05:10:09"
    }
  ]
}
```

### MedGemma AI Analysis

#### Analyze Imaging Study
```http
POST /medgemma/analyze/imaging/{study_id}
```

**Response:**
```json
{
  "ai_result_id": 17,
  "result": {
    "study_uuid": "659f0151-cf49-4087-82a3-3ec28144a3a4",
    "modality": "MG",
    "findings": ["No critical abnormality detected"],
    "impression": "Unremarkable study",
    "recommendations": ["Clinical correlation recommended"]
  }
}
```

#### Analyze Lab Results
```http
POST /medgemma/analyze/labs/{patient_id}
```

**Response:**
```json
{
  "patient_id": 1,
  "lab_comments": [
    "White Blood Cells is within normal limits (9.52)."
  ]
}
```

#### Get Combined Second Opinion
```http
POST /medgemma/second-opinion/{patient_id}
```

**Response:**
```json
{
  "patient_id": 1,
  "imaging": [
    {
      "study_uuid": "659f0151-cf49-4087-82a3-3ec28144a3a4",
      "modality": "MG",
      "findings": ["No critical abnormality detected"],
      "impression": "Unremarkable study",
      "recommendations": ["Clinical correlation recommended"]
    }
  ],
  "labs": [
    "White Blood Cells is within normal limits (9.52)."
  ],
  "medications": [
    {
      "medication": "Prenatal Vitamins",
      "dosage": "as directed"
    }
  ]
}
```

## Error Responses

### 404 Not Found
```json
{
  "message": "No query results for model [App\\Models\\Patient] 999"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "field_name": ["The field is required."]
  }
}
```

### 500 Internal Server Error
```json
{
  "message": "Server Error"
}
```

## Data Models

### Patient
- `id`: Integer, primary key
- `uuid`: String, unique identifier
- `mrn`: String, medical record number
- `first_name`: String
- `last_name`: String
- `dob`: Date, date of birth
- `sex`: Enum (male, female, other, unknown)

### Imaging Study
- `id`: Integer, primary key
- `patient_id`: Foreign key to patients
- `uuid`: String, unique identifier
- `description`: String
- `modality`: String (XR, CT, MRI, US, etc.)
- `started_at`: DateTime
- `status`: String

### Lab Order
- `id`: Integer, primary key
- `patient_id`: Foreign key to patients
- `test_id`: Foreign key to lab_tests
- `status`: String
- `priority`: String
- `result_value`: String
- `result_flag`: String (normal, high, low, critical)
- `result_notes`: Text

### AI Result
- `id`: Integer, primary key
- `imaging_study_id`: Foreign key to imaging_studies
- `model`: String, AI model name
- `status`: String (pending, completed, failed)
- `confidence_score`: Float (0.0 to 1.0)
- `result`: JSON, analysis results

## Rate Limiting

Currently no rate limiting is implemented. For production, consider implementing:
- 100 requests per minute per IP for public endpoints
- 1000 requests per minute for authenticated users
- Special limits for AI analysis endpoints

## Security Notes

**Important**: This is a demo application. For production use with real medical data:

1. **Authentication Required**: Implement proper authentication (OAuth2, JWT)
2. **Authorization**: Role-based access control (RBAC)
3. **HTTPS Only**: Force SSL/TLS encryption
4. **Input Validation**: Validate and sanitize all inputs
5. **Audit Logging**: Log all API access and data changes
6. **HIPAA Compliance**: Implement healthcare data protection standards
7. **Rate Limiting**: Prevent abuse and DoS attacks
8. **API Versioning**: Version your API for backward compatibility

## Integration Examples

### JavaScript/Fetch
```javascript
// Get patients list
const response = await fetch('/api/reports/patients');
const data = await response.json();

// Analyze imaging study
const analysisResponse = await fetch('/api/medgemma/analyze/imaging/1', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  }
});
```

### cURL
```bash
# Get patient details
curl -X GET "https://yourdomain.com/api/reports/patients/1" \
  -H "Accept: application/json"

# Trigger lab analysis
curl -X POST "https://yourdomain.com/api/medgemma/analyze/labs/1" \
  -H "Accept: application/json"
```

### Python/Requests
```python
import requests

# Get patients
response = requests.get('https://yourdomain.com/api/reports/patients')
patients = response.json()

# Analyze imaging
analysis = requests.post('https://yourdomain.com/api/medgemma/analyze/imaging/1')
result = analysis.json()
```