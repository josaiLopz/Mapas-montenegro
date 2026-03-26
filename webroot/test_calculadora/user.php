<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Calculadora · Acceso</title>

<script src="https://cdnjs.cloudflare.com/ajax/libs/react/18.2.0/umd/react.production.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/react-dom/18.2.0/umd/react-dom.production.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/babel-standalone/7.23.2/babel.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
  --brand:#aa2334; --brand-soft:#fbe9ec;
  --radius-sm:6px; --radius-md:12px; --radius-lg:20px;
  --font-sans:'Plus Jakarta Sans',system-ui,sans-serif;
  --font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace;
}
:root[data-theme="dark"]{
  --bg:#0f0f16; --surface:#18181f; --surface-2:#222230; --border:#2e2e3e;
  --accent:var(--brand); --accent-soft:rgba(170,35,52,.18); --accent-text:#f8b4c0;
  --danger:#e57373; --success:#66bb6a; --success-text:#0c2b16; --success-soft:rgba(102,187,106,.15);
  --text-primary:#eeeef5; --text-secondary:#9090a8; --text-tertiary:#5c5c70;
  --shadow-lg:0 20px 60px rgba(0,0,0,.6);
}
:root[data-theme="light"]{
  --bg:#f4f5fb; --surface:#ffffff; --surface-2:#f0f2f8; --border:#e0e6f0;
  --accent:var(--brand); --accent-soft:#fbe9ec; --accent-text:var(--brand);
  --danger:#c62828; --success:#2e7d32; --success-text:#fff; --success-soft:#e8f5e9;
  --text-primary:#1a1a2e; --text-secondary:#4a5070; --text-tertiary:#8890a8;
  --shadow-lg:0 20px 60px rgba(14,14,40,.12);
}
body{ background:var(--bg); color:var(--text-primary); font-family:var(--font-sans); min-height:100vh; -webkit-font-smoothing:antialiased; }

/* ── Login ── */
.login-wrap{min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
.login-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-lg);width:420px;max-width:100%;padding:40px;box-shadow:var(--shadow-lg);}
.login-logo{display:flex;align-items:center;gap:12px;margin-bottom:32px;}
.login-logo-text{font-size:20px;font-weight:700;color:var(--text-primary);}
.login-logo-text span{display:block;font-size:13px;font-weight:400;color:var(--text-secondary);}
.login-title{font-size:24px;font-weight:700;margin-bottom:8px;}
.login-sub{font-size:14px;color:var(--text-secondary);margin-bottom:28px;line-height:1.5;}
label{font-size:13px;font-weight:500;color:var(--text-secondary);display:block;margin-bottom:6px;}
input[type=email],input[type=text],input[type=number]{width:100%;background:var(--surface-2);border:1px solid var(--border);color:var(--text-primary);border-radius:var(--radius-sm);padding:12px 16px;font-family:var(--font-sans);font-size:15px;outline:none;transition:all .15s;}
input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-soft);}
input[type=checkbox]{width:18px;height:18px;accent-color:var(--accent);cursor:pointer;}
.btn{padding:12px 24px;border:1px solid transparent;border-radius:var(--radius-sm);cursor:pointer;font-family:var(--font-sans);font-size:15px;font-weight:600;transition:all .2s;display:inline-flex;align-items:center;justify-content:center;gap:8px;width:100%;}
.btn-primary{background:var(--accent);color:#fff;}.btn-primary:hover{filter:brightness(1.12);}
.btn-primary:disabled{opacity:.5;cursor:not-allowed;}
.btn-secondary{background:var(--surface-2);color:var(--text-primary);border-color:var(--border);}.btn-secondary:hover{background:var(--border);}
.btn-ghost{background:none;border:none;color:var(--text-secondary);cursor:pointer;font-size:13px;padding:8px 0;font-family:var(--font-sans);}
.btn-ghost:hover{color:var(--accent);}
.form-group{margin-bottom:20px;}
.error-msg{font-size:13px;color:var(--danger);background:rgba(229,115,115,.1);padding:10px 14px;border-radius:var(--radius-sm);margin-bottom:16px;}
.success-msg{font-size:13px;color:var(--success);background:var(--success-soft);padding:10px 14px;border-radius:var(--radius-sm);margin-bottom:16px;}

/* OTP inputs */
.otp-wrap{display:flex;gap:10px;justify-content:center;margin:24px 0;}
.otp-input{width:52px;height:64px;text-align:center;font-size:28px;font-weight:700;border-radius:var(--radius-md);background:var(--surface-2);border:2px solid var(--border);color:var(--text-primary);font-family:var(--font-mono);outline:none;transition:all .15s;caret-color:var(--accent);}
.otp-input:focus{border-color:var(--accent);box-shadow:0 0 0 3px var(--accent-soft);}
.otp-input.filled{border-color:var(--accent);background:var(--accent-soft);}

/* ── App layout ── */
.app-wrap{display:flex;flex-direction:column;min-height:100vh;}
.topbar{background:var(--surface);border-bottom:1px solid var(--border);padding:0 24px;height:64px;display:flex;align-items:center;justify-content:space-between;position:sticky;top:0;z-index:100;}
.topbar-brand{display:flex;align-items:center;gap:10px;font-size:16px;font-weight:700;}
.topbar-user{display:flex;align-items:center;gap:12px;font-size:14px;color:var(--text-secondary);}
.topbar-user strong{color:var(--text-primary);}
.topbar-actions{display:flex;align-items:center;gap:8px;}
.icon-btn{background:none;border:none;cursor:pointer;padding:8px;border-radius:var(--radius-sm);font-size:16px;color:var(--text-tertiary);transition:all .15s;display:inline-flex;align-items:center;}
.icon-btn:hover{background:var(--surface-2);color:var(--text-primary);}
.app-body{flex:1;padding:32px 24px;max-width:960px;margin:0 auto;width:100%;}

/* Lineas selector */
.linea-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;margin-bottom:32px;}
.linea-card{background:var(--surface);border:2px solid var(--border);border-radius:var(--radius-md);padding:24px 20px;cursor:pointer;transition:all .2s;text-align:center;}
.linea-card:hover{border-color:var(--accent);transform:translateY(-2px);box-shadow:var(--shadow-lg);}
.linea-card.selected{border-color:var(--accent);background:var(--accent-soft);}
.linea-name{font-size:16px;font-weight:700;margin-bottom:6px;}
.linea-price{font-size:24px;font-weight:700;color:var(--accent-text);}
.linea-price-lbl{font-size:11px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;}

/* Calculadora */
.calc-grid{display:grid;grid-template-columns:1fr 1fr;gap:20px;}
.calc-section{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-md);padding:24px;box-shadow:0 2px 8px rgba(0,0,0,.05);}
.calc-section-title{font-size:11px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--text-secondary);margin-bottom:18px;display:flex;align-items:center;gap:8px;}
.form-group-c{margin-bottom:18px;}
.frozen-field{background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-sm);padding:11px 16px;font-size:15px;font-weight:600;color:var(--accent-text);display:flex;align-items:center;justify-content:space-between;}
.frozen-badge{font-size:10px;font-weight:600;background:var(--accent-soft);color:var(--accent-text);padding:2px 8px;border-radius:99px;margin-left:8px;}
.checkbox-row{display:flex;align-items:center;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);}
.checkbox-row:last-child{border-bottom:none;}
.checkbox-row label{margin-bottom:0;font-size:15px;cursor:pointer;}
.metric{background:var(--surface-2);border-radius:var(--radius-md);padding:14px 18px;margin-bottom:12px;}
.metric-val{font-size:26px;font-weight:700;color:var(--accent-text);}
.metric-lbl{font-size:11px;color:var(--text-secondary);text-transform:uppercase;letter-spacing:.06em;margin-top:4px;}

/* Tabla resultados */
.results-card{background:var(--surface);border:1px solid var(--border);border-radius:var(--radius-md);overflow:hidden;box-shadow:0 4px 20px rgba(0,0,0,.06);margin-top:20px;}
.results-table{width:100%;border-collapse:collapse;font-size:14px;}
.results-table th{text-align:left;padding:12px 16px;background:var(--surface-2);color:var(--text-secondary);font-size:12px;font-weight:600;text-transform:uppercase;letter-spacing:.06em;border-bottom:1px solid var(--border);}
.results-table td{padding:12px 16px;border-bottom:1px solid var(--border);}
.results-table tr:last-child td{border-bottom:none;}
.results-table tr.final td{font-weight:700;font-size:20px;color:var(--accent-text);background:var(--accent-soft);}
.tag{display:inline-block;padding:3px 9px;border-radius:99px;font-size:11px;font-weight:600;}
.tag-ok{background:var(--success-soft);color:var(--success);}
.tag-off{background:var(--surface-2);color:var(--text-secondary);}
.tag-warn{background:var(--accent-soft);color:var(--accent-text);}

/* Precio final hero */
.precio-hero{background:linear-gradient(135deg,var(--accent) 0%,#c93a4d 100%);border-radius:var(--radius-md);padding:28px 32px;display:flex;align-items:center;justify-content:space-between;margin-top:20px;color:#fff;}
.precio-hero-val{font-size:48px;font-weight:900;letter-spacing:-1px;}
.precio-hero-lbl{font-size:13px;opacity:.8;margin-bottom:4px;}
.precio-hero-base{font-size:14px;opacity:.7;margin-top:4px;}

/* Toast */
.toast{position:fixed;bottom:24px;right:24px;padding:14px 24px;border-radius:var(--radius-md);font-size:15px;font-weight:500;z-index:9999;animation:su .3s ease;box-shadow:0 8px 32px rgba(0,0,0,.2);}
.toast-ok{background:var(--success);color:var(--success-text);}
.toast-warn{background:var(--danger);color:#fff;}
@keyframes su{from{transform:translateY(20px);opacity:0}to{transform:translateY(0);opacity:1}}

/* Loading */
.spinner-wrap{display:flex;flex-direction:column;align-items:center;justify-content:center;padding:80px 0;gap:16px;color:var(--text-secondary);}
.spinner-ring{width:40px;height:40px;border:3px solid var(--border);border-top-color:var(--accent);border-radius:50%;animation:spin 1s linear infinite;}
@keyframes spin{to{transform:rotate(360deg)}}

/* Timer OTP */
.otp-timer{font-size:12px;color:var(--text-tertiary);text-align:center;margin-top:8px;}
.otp-timer span{color:var(--danger);font-weight:600;}

@media(max-width:640px){.calc-grid{grid-template-columns:1fr}.otp-input{width:44px;height:56px;font-size:24px}.precio-hero{flex-direction:column;gap:12px}.precio-hero-val{font-size:36px}}
</style>
</head>
<body>
<div id="root"></div>
<script type="text/babel">
const {useState,useEffect,useCallback,useRef}=React;
const API_URL="api.php";

async function apiFetch(resource,method="GET",body=null,params={},token=null){
  const url=new URL(API_URL,window.location.href);
  url.searchParams.set("resource",resource);
  Object.entries(params).forEach(([k,v])=>url.searchParams.set(k,v));
  const headers={"Content-Type":"application/json"};
  if(token) headers["Authorization"]="Bearer "+token;
  const opts={method,headers};
  if(body) opts.body=JSON.stringify(body);
  const res=await fetch(url.toString(),opts);
  const data=await res.json();
  if(!res.ok) throw new Error(data.error||"HTTP "+res.status);
  return data;
}

// ── Motor cálculo (mismo que admin) ──
function computeValores(campos,calc){
  const activos=(campos||[]).filter(c=>c.activo);
  const byClave=Object.fromEntries(activos.map(c=>[c.clave,c]));
  const cache={},visiting=new Set();
  const n0=v=>{const n=parseFloat(v);return isNaN(n)?0:n;};
  const get=clave=>{
    if(Object.prototype.hasOwnProperty.call(cache,clave)) return cache[clave];
    if(visiting.has(clave)) return 0;
    visiting.add(clave);
    const c=byClave[clave];
    let val=0;
    if(!c){val=n0(calc?.[clave]);}
    else if(c.tipo==="checkbox"){val=!!calc?.[clave];}
    else if(c.tipo==="texto"){val=calc?.[clave]??"";}
    else if(c.tipo==="numero"){val=n0(calc?.[clave]);}
    else if(c.tipo==="suma"){val=(Array.isArray(c.formula)?c.formula:[]).reduce((s,k)=>s+n0(get(k)),0);}
    else if(c.tipo==="porcentaje"){
      const f=(c.formula&&typeof c.formula==="object")?c.formula:{};
      const num=n0(get(f.numerador)),den=n0(get(f.denominador));
      val=!den?0:f.tipo==="crecimiento"?((num-den)/den)*100:(num/den)*100;
    } else{val=n0(calc?.[clave]);}
    cache[clave]=val; visiting.delete(clave); return val;
  };
  activos.forEach(c=>get(c.clave)); return cache;
}

function getTramoValor(tramos,valor){
  if(!tramos?.length||valor===""||valor==null) return 0;
  const num=parseFloat(valor); if(isNaN(num)) return 0;
  const t=tramos.find(t=>num>=t.desde&&num<=(t.hasta>=999999?Infinity:t.hasta));
  return t?parseFloat(t.valor):0;
}

const fmt=n=>isNaN(n)?"—":"$"+parseFloat(n).toLocaleString("es-MX",{minimumFractionDigits:2,maximumFractionDigits:2});
const pctFmt=n=>isNaN(n)?"—":(n*100).toFixed(1)+"%";
const n0=v=>{const n=parseFloat(v);return isNaN(n)?0:n;};
const isComputed=c=>c?.tipo==="suma"||c?.tipo==="porcentaje";

// ── Toast ──
function Toast({msg,type,onClose}){
  useEffect(()=>{const t=setTimeout(onClose,3500);return()=>clearTimeout(t);},[]);
  return <div className={"toast toast-"+type}>{msg}</div>;
}

// ══════════════════════════════════════════════════════════
//  PASO 1 — Email
// ══════════════════════════════════════════════════════════
function StepEmail({onSent}){
  const [email,setEmail]=useState("");
  const [loading,setLoading]=useState(false);
  const [error,setError]=useState("");

  const submit=async()=>{
    if(!email.trim()){setError("Ingresa tu correo.");return;}
    setLoading(true); setError("");
    try{
      const r=await apiFetch("auth_solicitar","POST",{email});
      // En desarrollo el código puede venir en la respuesta
      onSent(email, r.dev_codigo||null);
    } catch(e){
      setError(e.message||"Error al enviar. Verifica que tu correo esté registrado.");
    }
    setLoading(false);
  };

  return(
    <div className="login-wrap">
      <div className="login-card">
        <img src="../img/logo.png" alt="Logo Montenegro" style={{maxWidth: '200px', display: 'block', margin: '0 auto 32px'}}/>
        <h1 className="login-title">Bienvenido</h1>
        <p className="login-sub">Ingresa tu correo electrónico registrado y te enviaremos un código de verificación de 6 dígitos.</p>
        {error&&<div className="error-msg"><i className="bi bi-exclamation-circle" style={{marginRight:6}}></i>{error}</div>}
        <div className="form-group">
          <label>Correo electrónico</label>
          <input type="email" value={email} onChange={e=>setEmail(e.target.value)} placeholder="tucorreo@ejemplo.com"
            onKeyDown={e=>e.key==="Enter"&&submit()}/>
        </div>
        <button className="btn btn-primary" onClick={submit} disabled={loading}>
          {loading?<><span className="spinner-ring" style={{width:18,height:18,borderWidth:2}}></span> Enviando…</>:<><i className="bi bi-send"></i> Enviar código</>}
        </button>
      </div>
    </div>
  );
}

// ══════════════════════════════════════════════════════════
//  PASO 2 — Código OTP
// ══════════════════════════════════════════════════════════
function StepOtp({email,devCodigo,onVerified,onBack}){
  const [digits,setDigits]=useState(["","","","","",""]);
  const [loading,setLoading]=useState(false);
  const [error,setError]=useState("");
  const [timer,setTimer]=useState(15*60); // 15 min en segundos
  const refs=Array.from({length:6},()=>useRef(null));

  useEffect(()=>{ refs[0].current?.focus(); },[]);
  useEffect(()=>{
    if(devCodigo){
      const d=devCodigo.split("").slice(0,6);
      setDigits([...d,...Array(6-d.length).fill("")]);
    }
  },[devCodigo]);
  useEffect(()=>{
    const t=setInterval(()=>setTimer(s=>s>0?s-1:0),1000);
    return()=>clearInterval(t);
  },[]);

  const timerStr=`${String(Math.floor(timer/60)).padStart(2,"0")}:${String(timer%60).padStart(2,"0")}`;

  const handleInput=(i,val)=>{
    const v=val.replace(/\D/g,"").slice(0,1);
    const nd=[...digits]; nd[i]=v; setDigits(nd);
    if(v&&i<5) refs[i+1].current?.focus();
    if(!v&&i>0) refs[i-1].current?.focus();
  };

  const handlePaste=(e)=>{
    const paste=e.clipboardData.getData("text").replace(/\D/g,"").slice(0,6);
    if(paste.length===6){
      setDigits(paste.split(""));
      refs[5].current?.focus();
    }
  };

  const handleKeyDown=(i,e)=>{
    if(e.key==="Backspace"&&!digits[i]&&i>0) refs[i-1].current?.focus();
  };

  const verify=async()=>{
    const code=digits.join("");
    if(code.length<6){setError("Ingresa los 6 dígitos.");return;}
    setLoading(true); setError("");
    try{
      const r=await apiFetch("auth_verificar","POST",{email,codigo:code});
      onVerified(r.token,r.usuario);
    } catch(e){
      setError(e.message||"Código incorrecto o expirado.");
      setDigits(["","","","","",""]); refs[0].current?.focus();
    }
    setLoading(false);
  };

  return(
    <div className="login-wrap">
      <div className="login-card">
        <img src="../img/logo.png" alt="Logo Montenegro" style={{maxWidth: '200px', display: 'block', margin: '0 auto 32px'}}/>
        <h1 className="login-title">Ingresa el código</h1>
        <p className="login-sub">Enviamos un código de 6 dígitos a <strong>{email}</strong>.</p>
        {devCodigo&&<div className="success-msg">🛠️ <strong>Modo desarrollo:</strong> el código es <strong style={{letterSpacing:4}}>{devCodigo}</strong> (SMTP no configurado)</div>}
        {error&&<div className="error-msg"><i className="bi bi-exclamation-circle" style={{marginRight:6}}></i>{error}</div>}
        <div className="otp-wrap" onPaste={handlePaste}>
          {digits.map((d,i)=>(
            <input key={i} ref={refs[i]} className={"otp-input"+(d?" filled":"")}
              type="text" inputMode="numeric" maxLength="1" value={d}
              onChange={e=>handleInput(i,e.target.value)}
              onKeyDown={e=>handleKeyDown(i,e)}/>
          ))}
        </div>
        <div className="otp-timer">Expira en <span>{timerStr}</span></div>
        <button className="btn btn-primary" style={{marginTop:20}} onClick={verify} disabled={loading||digits.join("").length<6}>
          {loading?<><span className="spinner-ring" style={{width:18,height:18,borderWidth:2}}></span> Verificando…</>:<><i className="bi bi-shield-check"></i> Verificar e ingresar</>}
        </button>
        <button className="btn-ghost" style={{marginTop:12,textAlign:"center",width:"100%"}} onClick={onBack}><i className="bi bi-arrow-left"></i> Usar otro correo</button>
      </div>
    </div>
  );
}

// ══════════════════════════════════════════════════════════
//  APP PRINCIPAL (usuario autenticado)
// ══════════════════════════════════════════════════════════
function UserApp({token,usuario,onLogout}){
  const [theme,setTheme]=useState(()=>localStorage.getItem("theme")||"dark");
  useEffect(()=>{ document.documentElement.dataset.theme=theme; localStorage.setItem("theme",theme); },[theme]);

  const [lineas,setLineas]=useState([]);
  const [selectedLinea,setSelectedLinea]=useState(null);
  const [campos,setCampos]=useState([]);
  const [discounts,setDiscounts]=useState([]);
  const [calc,setCalc]=useState({});
  const [loadingLinea,setLoadingLinea]=useState(false);
  const [toast,setToast]=useState(null);
  const [saved,setSaved]=useState(false);

  const showToast=(msg,type="ok")=>setToast({msg,type});
  const setC=(k,v)=>setCalc(p=>({...p,[k]:v}));
  const linea=lineas.find(l=>+l.id===selectedLinea);

  const fetchAPI=(res,method,body,params)=>apiFetch(res,method,body,params,token);

  // Cargar líneas activas
  useEffect(()=>{
    apiFetch("lineas").then(d=>setLineas((Array.isArray(d)?d:[]).filter(l=>l.activa)));
  },[]);

  // Cargar campos + descuentos + valores del usuario
  const loadLinea=async(lineaId)=>{
    setLoadingLinea(true); setSelectedLinea(lineaId); setSaved(false);
    try{
      const [camposData,descData,perfilData]=await Promise.all([
        apiFetch("campos","GET",null,{linea_id:lineaId}),
        apiFetch("descuentos","GET",null,{linea_id:lineaId}),
        fetchAPI("mi_perfil","GET",null,{linea_id:lineaId}),
      ]);

      const camposArr=Array.isArray(camposData)?camposData:[];
      const descArr=Array.isArray(descData)?descData:[];
      const userVals=perfilData?.valores||{};

      setCampos(camposArr);
      setDiscounts(descArr);

      // Init calc: valores del usuario para campos no-checkbox, false para checkboxes
      const init={};
      camposArr.filter(c=>c.activo&&!isComputed(c))
               .sort((a,b)=>(a.orden??0)-(b.orden??0))
               .forEach(c=>{
                 if(c.tipo==="checkbox") init[c.clave]=false; // checkboxes siempre en false al inicio
                 else init[c.clave]=userVals[c.clave]!==undefined?userVals[c.clave]:"";
               });
      setCalc(init);
    } catch(e){ showToast("Error cargando línea","warn"); }
    setLoadingLinea(false);
  };

  // Motor de cálculo
  const resultado=useCallback(()=>{
    if(!linea||!discounts.length) return null;
    const precioBase=parseFloat(linea.precio_base);
    const valores=campos.length?computeValores(campos,calc):null;
    const inputs=valores?{...valores}:{};
    campos.filter(c=>c.activo&&c.tipo==="checkbox").forEach(c=>{inputs[c.clave]=!!calc[c.clave];});

    const metaCampo=campos.find(c=>c.activo&&c.clave==="meta");
    const metaVal=metaCampo?n0(inputs.meta):0;
    const ventaTotal=n0(inputs.ventaTotal??0);
    const metaCumplida=metaVal>0&&ventaTotal>=metaVal;

    let precioActual=precioBase;
    const rows=[];
    [...discounts].sort((a,b)=>a.orden-b.orden).forEach(d=>{
      if(!d.activo) return;
      const bloq=d.sujeto_meta&&!metaCumplida;
      let pct=0;
      if(!bloq){
        if(d.escalonado){
          const v=inputs[d.depende_de];
          pct=getTramoValor(d.tramos,typeof v==="boolean"?(v?1:0):v);
        } else {
          pct=inputs[d.depende_de]?parseFloat(d.valor):0;
        }
      }
      const descAplicado=precioActual*pct;
      const resultado=precioActual-descAplicado;
      rows.push({...d,pct,descAplicado,resultado,metaCumplida,bloqueadoPorMeta:bloq});
      precioActual=resultado;
    });
    return{rows,precioFinal:precioActual,metaCumplida,valores:inputs};
  },[calc,discounts,linea,campos]);

  const res=resultado();

  const saveCotizacion=async()=>{
    if(!res) return;
    try{
      await fetchAPI("cotizaciones","POST",{
        linea_id:selectedLinea,
        inputs:res.valores,
        desglose:res.rows.map(r=>({nombre:r.nombre,pct:r.pct,descAplicado:r.descAplicado,resultado:r.resultado})),
        precio_final:res.precioFinal,
      });
      setSaved(true); showToast("Cotización guardada ✓");
    } catch{ showToast("Error guardando cotización","warn"); }
  };

  const camposActivos=(campos||[]).filter(c=>c.activo).sort((a,b)=>(a.orden??0)-(b.orden??0));
  const inputsCampos=camposActivos.filter(c=>!isComputed(c)&&c.tipo!=="checkbox");
  const checkboxesCampos=camposActivos.filter(c=>c.tipo==="checkbox");
  const metricasCampos=camposActivos.filter(c=>isComputed(c));

  // Agrupar inputs por sección
  const secciones=inputsCampos.reduce((acc,c)=>{
    const sec=c.seccion||"general"; if(!acc[sec]) acc[sec]=[]; acc[sec].push(c); return acc;
  },{});

  // Distinguir campos congelados (vienen de BD) vs checkboxes (interactivos)
  const isFrozen=c=>c.tipo!=="checkbox";

  const renderMetricaVal=c=>{
    const v=res?.valores?.[c.clave];
    if(c.tipo==="porcentaje") return n0(v).toFixed(1)+"%";
    return n0(v).toLocaleString("es-MX");
  };

  return(
    <div className="app-wrap">
      {/* Topbar */}
      <header className="topbar">
        <img src="../img/logo.png" alt="Logo Montenegro" style={{height: '40px'}}/>
        <div className="topbar-actions">
          <div className="topbar-user">
            <i className="bi bi-person-circle" style={{fontSize:18}}></i>
            <strong>{usuario.nombre}</strong>
          </div>
          <button className="icon-btn" title={theme==="dark"?"Modo claro":"Modo oscuro"} onClick={()=>setTheme(t=>t==="dark"?"light":"dark")}>
            <i className={"bi bi-"+(theme==="dark"?"sun":"moon-stars")}></i>
          </button>
          <button className="icon-btn" title="Cerrar sesión" onClick={onLogout}><i className="bi bi-box-arrow-right"></i></button>
        </div>
      </header>

      <div className="app-body">
        {/* Selección de línea */}
        <div style={{marginBottom:24}}>
          <h2 style={{fontSize:20,fontWeight:700,marginBottom:8}}>
            {selectedLinea?"Calculadora · "+linea?.nombre:"Selecciona una línea de producto"}
          </h2>
          {selectedLinea&&<p style={{fontSize:13,color:"var(--text-secondary)"}}>Precio base: <strong style={{color:"var(--accent-text)"}}>{fmt(linea?.precio_base)}</strong></p>}
        </div>

        <div className="linea-grid">
          {lineas.map(l=>(
            <div key={l.id} className={"linea-card"+(+selectedLinea===+l.id?" selected":"")}
              onClick={()=>loadLinea(+l.id)}>
              <div className="linea-name">{l.nombre}</div>
              <div className="linea-price">{fmt(l.precio_base)}</div>
              <div className="linea-price-lbl">precio base</div>
            </div>
          ))}
        </div>

        {/* Calculadora */}
        {selectedLinea&&(
          loadingLinea?(
            <div className="spinner-wrap"><div className="spinner-ring"></div><span>Cargando datos…</span></div>
          ):(
            campos.length===0?(
              <div style={{textAlign:"center",padding:"60px 0",color:"var(--text-secondary)"}}>
                <i className="bi bi-wrench-adjustable" style={{fontSize:40,display:"block",marginBottom:12}}></i>
                <p>Esta línea no tiene campos configurados aún.</p>
              </div>
            ):(
              <>
                <div className="calc-grid">
                  {/* Campos de entrada agrupados */}
                  {Object.entries(secciones).map(([sec,lista])=>(
                    <div className="calc-section" key={sec}>
                      <div className="calc-section-title">
                        <i className="bi bi-card-text"></i>
                        {sec==="general"?"Datos de Entrada":sec.charAt(0).toUpperCase()+sec.slice(1)}
                      </div>
                      {lista.map(c=>(
                        <div className="form-group-c" key={c.clave}>
                          <label>{c.etiqueta}</label>
                          {/* Campos numéricos vienen precargados y están congelados */}
                          <div className="frozen-field">
                            <span>{n0(calc[c.clave]).toLocaleString("es-MX")}</span>
                            <span className="frozen-badge">Precargado</span>
                          </div>
                        </div>
                      ))}
                    </div>
                  ))}

                  {/* Checkboxes — únicos interactivos */}
                  {checkboxesCampos.length>0&&(
                    <div className="calc-section">
                      <div className="calc-section-title"><i className="bi bi-check-circle"></i> Condiciones</div>
                      <p style={{fontSize:12,color:"var(--text-tertiary)",marginBottom:14}}>Marca las condiciones que aplican para esta venta.</p>
                      {checkboxesCampos.map(c=>(
                        <div className="checkbox-row" key={c.clave}>
                          <input type="checkbox" id={"u_chk_"+c.clave} checked={!!calc[c.clave]}
                            onChange={e=>setC(c.clave,e.target.checked)}/>
                          <label htmlFor={"u_chk_"+c.clave}>{c.etiqueta}</label>
                        </div>
                      ))}
                    </div>
                  )}

                  {/* Métricas calculadas */}
                  {metricasCampos.length>0&&res&&(
                    <div className="calc-section">
                      <div className="calc-section-title"><i className="bi bi-bar-chart-line"></i> Métricas</div>
                      {metricasCampos.map(c=>(
                        <div className="metric" key={c.clave}>
                          <div className="metric-val">{renderMetricaVal(c)}</div>
                          <div className="metric-lbl">{c.etiqueta}</div>
                        </div>
                      ))}
                      <div className="metric">
                        <div className="metric-val">{res.metaCumplida?"✅ Cumplida":"❌ No cumplida"}</div>
                        <div className="metric-lbl">Meta</div>
                      </div>
                    </div>
                  )}
                </div>

                {/* Desglose */}
                {res?.rows?.length>0&&(
                  <div className="results-card">
                    <table className="results-table">
                      <thead><tr><th>Descuento</th><th>%</th><th>Monto</th><th>Precio resultante</th><th>Estado</th></tr></thead>
                      <tbody>
                        <tr>
                          <td style={{fontWeight:600}}>Precio Base</td>
                          <td>—</td><td>—</td><td>{fmt(linea.precio_base)}</td><td></td>
                        </tr>
                        {res.rows.map(r=>(
                          <tr key={r.id}>
                            <td>{r.nombre}</td>
                            <td style={{color:r.pct>0?"var(--success)":"var(--text-tertiary)"}}>{pctFmt(r.pct)}</td>
                            <td style={{color:"var(--danger)"}}>{r.pct>0?"−"+fmt(r.descAplicado).replace("$",""):"—"}</td>
                            <td>{fmt(r.resultado)}</td>
                            <td>
                              {r.bloqueadoPorMeta?<span className="tag tag-warn">Sin meta</span>
                               :r.pct>0?<span className="tag tag-ok">Aplicado</span>
                               :<span className="tag tag-off">N/A</span>}
                            </td>
                          </tr>
                        ))}
                      </tbody>
                    </table>
                  </div>
                )}

                {/* Precio final hero */}
                {res&&(
                  <div className="precio-hero">
                    <div>
                      <div className="precio-hero-lbl">Precio Final</div>
                      <div className="precio-hero-val">{fmt(res.precioFinal)}</div>
                      <div className="precio-hero-base">Precio base: {fmt(linea.precio_base)}</div>
                    </div>
                    <div style={{display:"flex",flexDirection:"column",gap:12,alignItems:"flex-end"}}>
                      <button onClick={saveCotizacion}
                        style={{background:"rgba(255,255,255,.2)",border:"1px solid rgba(255,255,255,.4)",color:"#fff",padding:"10px 20px",borderRadius:8,cursor:"pointer",fontWeight:600,fontFamily:"var(--font-sans)",fontSize:14,display:"flex",alignItems:"center",gap:8}}>
                        <i className="bi bi-save"></i> Guardar cotización
                      </button>
                      {saved&&<span style={{fontSize:12,opacity:.8}}>✅ Guardado</span>}
                    </div>
                  </div>
                )}
                {res&&<p style={{fontSize:12,color:"var(--text-tertiary)",marginTop:12}}>* Descuentos "Sin meta" requieren cumplir la meta firmada.</p>}
              </>
            )
          )
        )}
      </div>
      {toast&&<Toast msg={toast.msg} type={toast.type} onClose={()=>setToast(null)}/>}
    </div>
  );
}

// ══════════════════════════════════════════════════════════
//  ROOT
// ══════════════════════════════════════════════════════════
function Root(){
  // Estado de autenticación (persiste en sessionStorage)
  const [authState,setAuthState]=useState(()=>{
    try{
      const s=sessionStorage.getItem("calc_session");
      return s?JSON.parse(s):null;
    } catch{ return null; }
  });
  const [step,setStep]=useState("email"); // email | otp
  const [sentEmail,setSentEmail]=useState("");
  const [devCodigo,setDevCodigo]=useState(null);

  useEffect(()=>{
    document.documentElement.dataset.theme=localStorage.getItem("theme")||"dark";
  },[]);

  const handleSent=(email,devCode)=>{ setSentEmail(email); setDevCodigo(devCode); setStep("otp"); };
  const handleVerified=(token,usuario)=>{
    const session={token,usuario};
    sessionStorage.setItem("calc_session",JSON.stringify(session));
    setAuthState(session);
  };
  const handleLogout=()=>{
    // Llamar al endpoint de logout
    if(authState?.token) apiFetch("auth_logout","POST",{},{"token":authState.token},authState.token).catch(()=>{});
    sessionStorage.removeItem("calc_session");
    setAuthState(null); setStep("email");
  };

  if(authState){
    return <UserApp token={authState.token} usuario={authState.usuario} onLogout={handleLogout}/>;
  }
  if(step==="otp"){
    return <StepOtp email={sentEmail} devCodigo={devCodigo} onVerified={handleVerified} onBack={()=>setStep("email")}/>;
  }
  return <StepEmail onSent={handleSent}/>;
}

ReactDOM.createRoot(document.getElementById("root")).render(<Root/>);
</script>
</body>
</html>
