<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>FHIR • DICOM • MedGemma • Dashboard</title>
    <style>
        :root{--bg:#f7f7f7;--card:#fff;--border:#e5e5e5;--muted:#666;--primary:#1f2937;--accent:#2563eb;--ok:#0a7f3f;--warn:#a16207;--error:#b91c1c}
        *{box-sizing:border-box}
        body{margin:0;font-family:system-ui,-apple-system,Segoe UI,Roboto,Ubuntu,Cantarell,Noto Sans,Helvetica,Arial,sans-serif;background:var(--bg);color:#111}
        header{background:var(--card);border-bottom:1px solid var(--border);padding:14px 20px;display:flex;justify-content:space-between;align-items:center}
        header h1{font-size:18px;margin:0}
        .container{max-width:1200px;margin:20px auto;padding:0 16px}
        .grid{display:grid;grid-template-columns:1fr 2fr;gap:16px}
        .card{background:var(--card);border:1px solid var(--border);border-radius:10px;padding:16px}
        .card h2{margin:0 0 10px 0;font-size:16px}
        .muted{color:var(--muted)}
        .tag{display:inline-block;padding:2px 8px;border-radius:999px;border:1px solid var(--border);font-size:12px;margin-right:6px}
        .tag.ok{color:var(--ok);border-color:#b7eb8f;background:#e6ffed}
        .tag.warn{color:var(--warn);border-color:#fde68a;background:#fffbeb}
        .tag.err{color:var(--error);border-color:#fecaca;background:#fef2f2}
        .list{max-height:520px;overflow:auto;border-top:1px solid var(--border)}
        .item{padding:10px 8px;border-bottom:1px solid var(--border);display:flex;justify-content:space-between;gap:8px;cursor:pointer}
        .item:hover{background:#fafafa}
        .btn{display:inline-flex;align-items:center;gap:6px;padding:8px 10px;border:1px solid var(--primary);color:#fff;background:var(--primary);border-radius:8px;cursor:pointer;font-size:14px}
        .btn.secondary{background:#fff;color:var(--primary)}
        .btn.small{padding:6px 8px;font-size:12px}
        .row{display:flex;gap:8px;flex-wrap:wrap}
        table{width:100%;border-collapse:collapse}
        th,td{padding:8px;border-bottom:1px solid var(--border);text-align:left;font-size:14px}
        pre{white-space:pre-wrap;word-wrap:break-word;background:#0b1020;color:#d2e1ff;padding:10px;border-radius:6px}
        a.link{color:var(--accent);text-decoration:none}
    </style>
</head>
<body>
<header>
    <h1>FHIR • DICOM • MedGemma • Dashboard</h1>
    <nav class="row">
        <a class="btn secondary" href="/" title="Laravel Welcome">Welcome</a>
        <a class="btn secondary" href="/admin/users" title="Admin Users (Basic Auth)">Admin Users</a>
    </nav>
</header>
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
                <input id="search" type="search" placeholder="Search by name or MRN" style="flex:1;padding:8px;border:1px solid var(--border);border-radius:8px">
                <button class="btn" onclick="loadPatients()">Reload</button>
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
            <button class="btn" onclick="analyzeLabs(${p.id})">Analyze Labs</button>
            <button class="btn" onclick="secondOpinion(${p.id})">Combined Second Opinion</button>
        `;

        // Imaging
        const im = p.imaging_studies || [];
        let imHtml = `<h3>Imaging Studies</h3>`;
        if (im.length === 0) imHtml += '<div class="muted">No imaging studies.</div>';
        else {
            imHtml += '<table><thead><tr><th>Modality</th><th>Description</th><th>Date</th><th>AI</th><th></th></tr></thead><tbody>';
            im.forEach(s => {
                const lastAI = (s.ai_results||[])[0];
                const aiCell = lastAI ? `${htmlesc(lastAI.model)} ${tag((lastAI.confidence_score||'').toString(),'ok')}` : '<span class="muted">None</span>';
                imHtml += `<tr>
                    <td>${htmlesc(s.modality||'-')}</td>
                    <td>${htmlesc(s.description||'-')}</td>
                    <td>${htmlesc(s.started_at||'-')}</td>
                    <td>${aiCell}</td>
                    <td><button class="btn small" onclick="analyzeImaging(${s.id})">Analyze</button></td>
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
            labsHtml += '<table><thead><tr><th>Test</th><th>Status</th><th>Priority</th><th>Result</th><th>Notes</th></tr></thead><tbody>';
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
            rxHtml += '<table><thead><tr><th>Medication</th><th>Strength</th><th>Dosage</th><th>Frequency</th><th>Status</th></tr></thead><tbody>';
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
