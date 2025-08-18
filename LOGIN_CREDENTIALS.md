# 🔐 Test Login Credentials - FHIR DICOM MedGemma Platform

## 🚀 Quick Access Methods

### 1. **Quick Login Page** (Automated Login)

**URL:** [http://localhost:8002/quick-login](http://localhost:8002/quick-login)

- **Admin Dashboard:** Click "Login as Admin" button
- **Doctor Dashboard:** Click "Login as Doctor" button
- No password required - automatic login for demo purposes

### 2. **Manual Login Page** (Traditional Login)

**URL:** [http://localhost:8002/login](http://localhost:8002/login)

- Use any of the credentials below
- Enter email and password manually

---

## 👥 User Types & Credentials

### 🔧 **ADMINISTRATORS**

| Name | Email | Password | Access Level |
|------|-------|----------|-------------|
| Admin User | `admin@medgemma.com` | `admin123` | Full system access, financial dashboard |
| System Administrator | `administrator@medgemma.com` | `admin123` | Full system access |

**Admin Features:**

- ✅ Complete business overview
- ✅ Doctor performance analytics  
- ✅ Revenue & expense tracking
- ✅ User management
- ✅ System configuration

---

### 👨‍⚕️ **DOCTORS**

| Name | Email | Password | Specialization |
|------|-------|----------|----------------|
| Dr. Sarah Johnson | `doctor1@medgemma.com` | `doctor123` | Primary Doctor |
| Dr. Michael Chen | `doctor2@medgemma.com` | `doctor123` | Secondary Doctor |
| Dr. Emily Rodriguez | `doctor@medgemma.com` | `doctor123` | General Practice |

**Doctor Features:**

- ✅ Personal earnings tracker
- ✅ Patient management
- ✅ Revenue share visualization
- ✅ AI analysis tools (MedGemma)
- ✅ DICOM image analysis

---

### 🩻 **RADIOLOGISTS**

| Name | Email | Password | Department |
|------|-------|----------|------------|
| Dr. James Wilson | `radiologist@medgemma.com` | `radio123` | Radiology Head |
| Dr. Lisa Parker | `radiologist2@medgemma.com` | `radio123` | Senior Radiologist |

**Radiologist Features:**

- ✅ DICOM image analysis
- ✅ AI-powered radiology reports
- ✅ Imaging study management

---

### 👩‍⚕️ **NURSES**

| Name | Email | Password | Unit |
|------|-------|----------|------|
| Nurse Jennifer Smith | `nurse@medgemma.com` | `nurse123` | General Ward |
| Nurse Robert Brown | `nurse2@medgemma.com` | `nurse123` | ICU |

**Nurse Features:**

- ✅ Patient care management
- ✅ Basic medical records access
- ✅ Medication tracking

---

### 🔬 **LAB TECHNICIANS**

| Name | Email | Password | Lab Section |
|------|-------|----------|-------------|
| Lab Tech Maria Garcia | `labtech@medgemma.com` | `lab123` | General Lab |
| Lab Tech David Lee | `labtech2@medgemma.com` | `lab123` | Microbiology |

**Lab Tech Features:**

- ✅ Lab result entry
- ✅ Sample tracking
- ✅ Quality control

---

### 💊 **PHARMACISTS**

| Name | Email | Password | Pharmacy |
|------|-------|----------|----------|
| Pharmacist Anna Davis | `pharmacist@medgemma.com` | `pharma123` | Main Pharmacy |
| Pharmacist John Martinez | `pharmacist2@medgemma.com` | `pharma123` | Emergency Pharmacy |

**Pharmacist Features:**

- ✅ Prescription management
- ✅ Drug interaction checking
- ✅ Inventory management

---

### 🔬 **PATHOLOGISTS**

| Name | Email | Password | Specialty |
|------|-------|----------|-----------|
| Dr. Patricia Taylor | `pathologist@medgemma.com` | `patho123` | Surgical Pathology |
| Dr. Mark Anderson | `pathologist2@medgemma.com` | `patho123` | Clinical Pathology |

**Pathologist Features:**

- ✅ Pathology report generation
- ✅ Tissue analysis
- ✅ Diagnostic consultation

---

### 📞 **RECEPTIONISTS**

| Name | Email | Password | Department |
|------|-------|----------|------------|
| Receptionist Susan White | `receptionist@medgemma.com` | `reception123` | Front Desk |
| Receptionist Carlos Hernandez | `receptionist2@medgemma.com` | `reception123` | Appointment Desk |

**Receptionist Features:**

- ✅ Patient registration
- ✅ Appointment scheduling
- ✅ Basic information access

---

### 🏥 **PATIENTS**

| Name | Email | Password | Status |
|------|-------|----------|--------|
| Patient John Doe | `patient@medgemma.com` | `patient123` | Active Patient |
| Patient Jane Smith | `patient2@medgemma.com` | `patient123` | Active Patient |

**Patient Features:**

- ✅ Personal medical records
- ✅ Appointment viewing
- ✅ Test results access

---

## 🌐 Application URLs

| Page | URL | Description |
|------|-----|-------------|
| **Quick Login** | <http://localhost:8002/quick-login> | Automated demo login |
| **Manual Login** | <http://localhost:8002/login> | Traditional login form |
| **Dashboard** | <http://localhost:8002/dashboard> | Main application dashboard |
| **Patients** | <http://localhost:8002/patients> | Patient management |
| **MedGemma AI** | <http://localhost:8002/medgemma> | AI analysis tools |
| **Reports** | <http://localhost:8002/reports> | Medical reports |
| **DICOM Upload** | <http://localhost:8002/dicom-upload> | Medical imaging |
| **Financial Admin** | <http://localhost:8002/financial/admin-dashboard> | Admin financial view |
| **Financial Doctor** | <http://localhost:8002/financial/doctor-dashboard> | Doctor earnings |

---

## 🔒 Security Notes

- **Development Environment:** These are test credentials for development only
- **Production:** Never use these credentials in production
- **Password Policy:** All test passwords follow simple patterns for demo purposes
- **Session Management:** 30-minute timeout for security

---

## 🚨 Troubleshooting

### If login fails

1. **Check server status:** Ensure Laravel is running on port 8002
2. **Clear browser cache:** Force refresh the login page
3. **Verify credentials:** Copy-paste email/password exactly as shown
4. **Check logs:** View Laravel logs for authentication errors

### Quick test commands

```bash
# Test API login
curl -X POST "http://localhost:8002/test-login" \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@medgemma.com","password":"admin123"}'

# Recreate test users
php artisan create:test-users
```

---

## 📱 Demo Features Available

### For Admins

- Complete financial dashboard with 30 days of sample data
- Revenue sharing analytics (60-70% doctors, 30-40% admin)
- Expense tracking with categories
- User management system
- Real-time charts and analytics

### For Doctors

- Personal earnings tracker
- Patient count & appointments
- Revenue share visualization
- AI-powered analysis tools (MedGemma)
- DICOM image analysis

### For All Users

- Patient management with encrypted data protection
- Medical records access (role-based)
- Report generation
- Secure authentication system

---

**🎉 Ready to test! Start with the Quick Login page for the fastest access.**
