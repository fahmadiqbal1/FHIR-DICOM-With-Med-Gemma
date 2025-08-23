# 🌐 Server Configuration Guide

## ✅ Fixed Port Conflicts - Both Services Running Successfully!

### 🏥 **FHIR-DICOM Healthcare Platform**
- **URL**: `http://localhost:8090`
- **Purpose**: Healthcare management system with multi-role access
- **Technology**: Laravel 11 + PHP 8.4

### 🤖 **MedGemma AI Server**
- **URL**: `http://localhost:8000`
- **Purpose**: Medical AI analysis and natural language processing
- **Technology**: FastAPI + Python + Transformers

### 🧠 **Ollama AI Models**
- **URL**: `http://localhost:11434`
- **Purpose**: Local LLM server for AI model inference
- **Technology**: Ollama runtime

---

## 🔗 Quick Access Links

### Healthcare Platform Access:
- **Main Login**: [http://localhost:8090/login](http://localhost:8090/login)
- **Quick Login Demo**: [http://localhost:8090/quick-login](http://localhost:8090/quick-login)
- **Admin Dashboard**: [http://localhost:8090/dashboard](http://localhost:8090/dashboard)

### Test Credentials:
```
👨‍💼 Admin: admin@test.com / admin123
👨‍⚕️ Doctor: doctor@test.com / doctor123  
🔬 Radiologist: radiologist@test.com / radio123
🧪 Lab Tech: labtech@test.com / lab123
```

### AI Services:
- **MedGemma API**: [http://localhost:8000/docs](http://localhost:8000/docs)
- **Ollama Models**: [http://localhost:11434](http://localhost:11434)

---

## 🚀 How to Start Services

### 1. Laravel Healthcare Platform:
```bash
cd /Users/fahmadiqbal/FHIR-DICOM-With-Med-Gemma/backend
php artisan serve --host=0.0.0.0 --port=8090
```

### 2. MedGemma AI Server:
```bash
cd /Users/fahmadiqbal/FHIR-DICOM-With-Med-Gemma
python3 medgemma_server.py --host 0.0.0.0 --port 8000
```

### 3. Ollama (if not running):
```bash
ollama serve
```

---

## 🔧 Troubleshooting

### Port Conflicts Resolution:
- **Healthcare Platform**: Uses port 8090 (changed from 8000)
- **MedGemma AI**: Uses port 8000 (original)
- **Ollama**: Uses port 11434 (default)

### Common Issues:
1. **"Could not open input file: artisan"**
   - Solution: Ensure you're in the `backend` directory
   - Run: `composer install` first

2. **Port already in use**
   - Check: `lsof -i :PORT_NUMBER`
   - Kill process: `kill -9 PID`

3. **Dependencies missing**
   - Laravel: `composer install`
   - Python: `pip install -r requirements.txt`

---

## 📊 Current Status:
- ✅ Healthcare Platform: Running on port 8090
- ✅ MedGemma AI Server: Running on port 8000  
- ✅ Ollama AI Models: Running on port 11434
- ✅ No port conflicts detected
- ✅ All test credentials active

**All services are now accessible and working independently!**
