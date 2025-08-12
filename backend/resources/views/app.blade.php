<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'MedGemma Healthcare Platform') }} • Dashboard</title>
    <link rel="stylesheet" href="/assets/app.css">
</head>
<body>
<header class="app-header"><div class="inner"><div class="logo"><div class="mark"></div><span>FHIR • DICOM • MedGemma</span></div><nav class="nav"><a class="btn ghost" href="/" title="Laravel Welcome">Welcome</a><a class="btn ghost" href="/dicom-upload" title="DICOM Upload & FHIR">DICOM Upload</a><a class="btn ghost" href="/admin/users" title="Admin Users (Basic Auth)">Admin Users</a></nav></div></header>
<div class="container">
    <div class="grid">
        <div class="card">
            <h2>MedGemma Integration</h2>
            <div id="medgemmaStatus" class="muted">Loading...</div>
        </div>
        <div class="card">
            <h2>Quick Tips</h2>
            <ul>
                <li>Use the left list to select a patient. Demo data is seeded.</li>
                <li>Trigger AI analyses from the patient details panel.</li>
                <li>Admin panel: <span class="tag">/admin/users</span> (Basic Auth via .env)</li>
            </ul>
        </div>
    </div>

    <div class="grid" style="margin-top:16px">
        <div class="card">
            <h2>Patients</h2>
            <div class="row" style="margin-bottom:8px">
                <input id="search" type="search" placeholder="Search by name or MRN" class="input" style="flex:1">
                <button class="btn primary" onclick="loadPatients()">Reload</button>
            </div>
            <div id="patients" class="list" role="listbox" aria-label="Patients list"></div>
        </div>
        <div class="card">
            <h2 id="patientTitle">Patient Details</h2>
            <div id="patientMeta" class="muted">Select a patient to view details.</div>
            <div id="patientActions" class="row" style="margin:10px 0; display:none"></div>

            <div id="patientImaging"></div>
            <div id="patientLabs" style="margin-top:16px"></div>
            <div id="patientRx" style="margin-top:16px"></div>
            <div id="patientNotes" style="margin-top:16px"></div>
        </div>
    </div>
</div>
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
let patientsCache = [];
let currentPatientId = null;

function tag(text, cls='') { return `<span class="tag ${cls}">${text}</span>`; }

async function loadMedGemma() {
    const el = document.getElementById('medgemmaStatus');
    try {
        const r = await fetch('/integrations/medgemma');
        const d = await r.json();
        const parts = [
            `Model: <b>${d.model || 'medgemma'}</b>`,
            d.enabled ? tag('enabled','ok') : tag('disabled','warn'),
            d.configured ? tag('configured','ok') : tag('not configured','warn')
        ];
        el.innerHTML = parts.join(' ');
    } catch (e) {
        el.innerHTML = 'Failed to load MedGemma status.';
    }
}

function renderPatients(list) {
    const box = document.getElementById('patients');
    if (!list || list.length === 0) {
        box.innerHTML = '<div class="muted" style="padding:10px">No patients</div>';
        return;
    }
    box.innerHTML = list.map(p => {
        const name = p.name || `${p.first_name || ''} ${p.last_name || ''}`.trim();
        return `<div class="item" role="option" onclick="selectPatient(${p.id})">
            <div>
                <div><b>${name}</b></div>
                <div class="muted">MRN: ${p.mrn || '-'} • DOB: ${p.dob || '-'} • ${p.sex || '-'}</div>
            </div>
            <div>
                ${tag(`Img ${p.counts.imaging_studies}`)}
                ${tag(`Lab ${p.counts.lab_orders}`)}
                ${tag(`Rx ${p.counts.prescriptions}`)}
            </div>
        </div>`
    }).join('');
}

async function loadPatients() {
    const q = document.getElementById('search').value.trim().toLowerCase();
    try {
        const r = await fetch('/reports/patients', {headers: {'Accept':'application/json'}});
        const d = await r.json();
        patientsCache = d.data || [];
        const filtered = q ? patientsCache.filter(p => (p.name||'').toLowerCase().includes(q) || (p.mrn||'').toLowerCase().includes(q)) : patientsCache;
        renderPatients(filtered);
    } catch (e) {
        document.getElementById('patients').innerHTML = '<div class="muted" style="padding:10px">Failed to load patients</div>';
    }
}

function htmlesc(str){return (str||'').toString().replace(/[&<>\"]/g, s=>({"&":"&amp;","<":"&lt;",">":"&gt;","\"":"&quot;"}[s]));}

async function selectPatient(id) {
    currentPatientId = id;
    document.getElementById('patientTitle').innerText = 'Patient Details';
    document.getElementById('patientMeta').innerText = 'Loading...';
    document.getElementById('patientActions').style.display = 'none';
    document.getElementById('patientImaging').innerHTML = '';
    document.getElementById('patientLabs').innerHTML = '';
    document.getElementById('patientRx').innerHTML = '';
    document.getElementById('patientNotes').innerHTML = '';

    try {
        const r = await fetch(`/reports/patients/${id}`, {headers: {'Accept':'application/json'}});
        const p = await r.json();
        const title = `${p.name || (p.first_name||'')+' '+(p.last_name||'')}`.trim();
        document.getElementById('patientTitle').innerText = title;
        document.getElementById('patientMeta').innerHTML = `MRN: <b>${htmlesc(p.mrn||'-')}</b> • DOB: ${htmlesc(p.dob||'-')} • ${htmlesc(p.sex||'-')}`;

        // Actions
        const actions = document.getElementById('patientActions');
        actions.style.display = 'flex';
        actions.innerHTML = `
            <button class="btn primary" onclick="analyzeLabs(${p.id})">Analyze Labs</button>
            <button class="btn ghost" onclick="secondOpinion(${p.id})">Combined Second Opinion</button>
        `;

        // Imaging
        const im = p.imaging_studies || [];
        let imHtml = `<h3>Imaging Studies</h3>`;
        if (im.length === 0) imHtml += '<div class="muted">No imaging studies.</div>';
        else {
            imHtml += '<table class="table"><thead><tr><th>Modality</th><th>Description</th><th>Date</th><th>AI</th><th></th></tr></thead><tbody>';
            im.forEach(s => {
                const lastAI = (s.ai_results||[])[0];
                const aiCell = lastAI ? `${htmlesc(lastAI.model)} ${tag((lastAI.confidence_score||'').toString(),'ok')}` : '<span class="muted">None</span>';
                imHtml += `<tr>
                    <td>${htmlesc(s.modality||'-')}</td>
                    <td>${htmlesc(s.description||'-')}</td>
                    <td>${htmlesc(s.started_at||'-')}</td>
                    <td>${aiCell}</td>
                    <td><button class="btn small primary" onclick="analyzeImaging(${s.id})">Analyze</button></td>
                </tr>`;
                if (lastAI && lastAI.result) {
                    imHtml += `<tr><td colspan="5"><pre>${htmlesc(JSON.stringify(lastAI.result,null,2))}</pre></td></tr>`;
                }
            });
            imHtml += '</tbody></table>';
        }
        document.getElementById('patientImaging').innerHTML = imHtml;

        // Labs
        const labs = p.lab_orders || [];
        let labsHtml = `<h3>Lab Orders</h3>`;
        if (labs.length === 0) labsHtml += '<div class="muted">No labs.</div>';
        else {
            labsHtml += '<table class="table"><thead><tr><th>Test</th><th>Status</th><th>Priority</th><th>Result</th><th>Notes</th></tr></thead><tbody>';
            labs.forEach(o => {
                labsHtml += `<tr>
                    <td>${htmlesc(o.code || '')} ${htmlesc(o.name||'')}</td>
                    <td>${htmlesc(o.status||'')}</td>
                    <td>${htmlesc(o.priority||'')}</td>
                    <td>${htmlesc(o.result_value||'')} ${o.result_flag?tag(htmlesc(o.result_flag),o.result_flag==='critical'?'err':(o.result_flag==='normal'?'ok':'warn')):''}</td>
                    <td>${htmlesc(o.result_notes||'')}</td>
                </tr>`;
            });
            labsHtml += '</tbody></table>';
        }
        document.getElementById('patientLabs').innerHTML = labsHtml;

        // Prescriptions
        const rx = p.prescriptions || [];
        let rxHtml = `<h3>Prescriptions</h3>`;
        if (rx.length === 0) rxHtml += '<div class="muted">No prescriptions.</div>';
        else {
            rxHtml += '<table class="table"><thead><tr><th>Medication</th><th>Strength</th><th>Dosage</th><th>Frequency</th><th>Status</th></tr></thead><tbody>';
            rx.forEach(r => {
                rxHtml += `<tr>
                    <td>${htmlesc(r.medication||'')}</td>
                    <td>${htmlesc(r.strength||'')}</td>
                    <td>${htmlesc(r.dosage||'')}</td>
                    <td>${htmlesc(r.frequency||'')}</td>
                    <td>${htmlesc(r.status||'')}</td>
                </tr>`;
            });
            rxHtml += '</tbody></table>';
        }
        document.getElementById('patientRx').innerHTML = rxHtml;

        // Notes
        const notes = p.clinical_notes || [];
        let noteHtml = `<h3>Clinical Notes</h3>`;
        if (notes.length === 0) noteHtml += '<div class="muted">No notes.</div>';
        else {
            notes.forEach(n => {
                noteHtml += `<div class="card" style="margin:8px 0">
                    <div class="muted">${htmlesc(n.created_at||'')}</div>
                    <div><b>Assessment:</b> ${htmlesc(n.soap_assessment||'')}</div>
                    <div><b>Plan:</b><br><pre>${htmlesc(n.soap_plan||'')}</pre></div>
                </div>`;
            });
        }
        document.getElementById('patientNotes').innerHTML = noteHtml;
    } catch (e) {
        document.getElementById('patientMeta').innerText = 'Failed to load patient.';
    }
}

async function postJson(url) {
    const r = await fetch(url, {method:'POST', headers:{'X-CSRF-TOKEN': csrf, 'Accept':'application/json'}});
    if (!r.ok) throw new Error('Request failed');
    return r.json().catch(()=>({ok:true}))
}

async function analyzeImaging(studyId){
    try { await postJson(`/medgemma/analyze/imaging/${studyId}`); if (currentPatientId) await selectPatient(currentPatientId); }
    catch(e){ alert('Failed to analyze imaging'); }
}
async function analyzeLabs(patientId){
    try { await postJson(`/medgemma/analyze/labs/${patientId}`); if (currentPatientId) await selectPatient(currentPatientId); }
    catch(e){ alert('Failed to analyze labs'); }
}
async function secondOpinion(patientId){
    try { await postJson(`/medgemma/second-opinion/${patientId}`); if (currentPatientId) await selectPatient(currentPatientId); }
    catch(e){ alert('Failed to get second opinion'); }
}

// Init
loadMedGemma();
loadPatients();
document.getElementById('search').addEventListener('input', () => {
    const q = document.getElementById('search').value.trim().toLowerCase();
    const filtered = q ? patientsCache.filter(p => (p.name||'').toLowerCase().includes(q) || (p.mrn||'').toLowerCase().includes(q)) : patientsCache;
    renderPatients(filtered);
});
</script>
</body>
</html>
