<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Rashoighor Ledger')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,500;9..144,700&family=Space+Mono:wght@400;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --ink:#F4EEDD;
    --bg:#1C2B22;
    --bg-panel:#233829;
    --turmeric:#E3A008;
    --chili:#B5452F;
    --sage:#5C7A63;
    --line: rgba(244,238,221,0.15);
  }
  *{box-sizing:border-box;}
  body{
    margin:0;
    background:var(--bg);
    color:var(--ink);
    font-family:'Inter',sans-serif;
    padding:0 0 80px;
  }
  a{color:inherit;text-decoration:none;}

  /* top nav */
  .topbar{
    border-bottom:1px solid var(--line);
    padding:18px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    max-width:900px;
    margin:0 auto;
    flex-wrap:wrap;
    gap:12px;
  }
  .brand{
    font-family:'Fraunces',serif;
    font-weight:700;
    font-size:18px;
    letter-spacing:.01em;
  }
  .brand span{color:var(--turmeric);}
  nav.links{
    display:flex;
    gap:18px;
    font-family:'Space Mono',monospace;
    font-size:12px;
    text-transform:uppercase;
    letter-spacing:.06em;
  }
  nav.links a{
    opacity:.65;
    padding-bottom:4px;
    border-bottom:2px solid transparent;
    transition:opacity .15s, border-color .15s;
  }
  nav.links a:hover{opacity:1;}
  nav.links a.active{opacity:1;border-color:var(--turmeric);}

  .login-widget{
    display:flex;
    align-items:center;
    gap:8px;
    font-family:'Space Mono',monospace;
    font-size:12px;
  }
  .login-widget input{
    background:transparent;
    border:none;
    border-bottom:1px solid var(--line);
    color:var(--ink);
    font-family:'Space Mono',monospace;
    font-size:12px;
    padding:5px 2px;
    width:110px;
    outline:none;
  }
  .login-widget input:focus{border-bottom-color:var(--turmeric);}
  .login-widget button{
    background:var(--turmeric);
    color:var(--bg);
    border:none;
    font-family:'Space Mono',monospace;
    font-weight:700;
    font-size:11px;
    text-transform:uppercase;
    padding:7px 12px;
    border-radius:3px;
    cursor:pointer;
  }
  .login-widget button:hover{background:#F0B32C;}
  .token-dot{
    width:8px;height:8px;border-radius:50%;
    background:var(--chili);
    display:inline-block;
  }
  .token-dot.on{background:#8FBF8A;}

  header.page-head{
    padding:48px 24px 32px;
    max-width:900px;
    margin:0 auto;
  }
  .eyebrow{
    font-family:'Space Mono',monospace;
    text-transform:uppercase;
    letter-spacing:.14em;
    font-size:12px;
    color:var(--turmeric);
    margin-bottom:14px;
  }
  h1{
    font-family:'Fraunces',serif;
    font-weight:700;
    font-size:38px;
    line-height:1.08;
    margin:0 0 12px;
  }
  .sub{opacity:.72;font-size:16px;max-width:560px;line-height:1.5;}

  main{
    max-width:900px;
    margin:0 auto;
    padding:8px 24px 0;
    display:flex;
    flex-direction:column;
    gap:28px;
  }
  .stall{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:4px;
    padding:28px;
    position:relative;
  }
  .stall h2{
    font-family:'Fraunces',serif;
    font-size:22px;
    margin:0 0 6px;
    font-weight:600;
  }
  .stall p.desc{font-size:14px;opacity:.68;margin:0 0 18px;line-height:1.5;}

  input.field{
    background:transparent;
    border:none;
    border-bottom:1px solid var(--line);
    color:var(--ink);
    font-family:'Space Mono',monospace;
    font-size:14px;
    padding:8px 2px;
    width:220px;
    margin:0 10px 12px 0;
    outline:none;
  }
  input.field:focus{border-bottom-color:var(--turmeric);}
  button.action{
    background:var(--turmeric);
    color:var(--bg);
    border:none;
    font-family:'Space Mono',monospace;
    font-weight:700;
    font-size:13px;
    text-transform:uppercase;
    letter-spacing:.05em;
    padding:11px 20px;
    border-radius:3px;
    cursor:pointer;
    transition:transform .1s ease, background .15s ease;
  }
  button.action:hover{background:#F0B32C; transform:translateY(-1px);}

  .status{font-family:'Space Mono',monospace;font-size:12px;margin-top:10px;min-height:16px;}
  .status.ok{color:#8FBF8A;}
  .status.err{color:var(--chili);}

  .receipt{margin-top:16px;border-top:1px dashed var(--line);padding-top:14px;display:none;}
  .receipt.show{display:block;}
  .food-row, .item-row{
    display:flex;justify-content:space-between;align-items:baseline;
    padding:8px 0;border-bottom:1px dotted var(--line);font-size:14px;
  }
  .food-row:last-child, .item-row:last-child{border-bottom:none;}
  .food-name{font-weight:600;}
  .food-meta{font-family:'Space Mono',monospace;font-size:12px;opacity:.6;}
  .price-tag{font-family:'Space Mono',monospace;color:var(--turmeric);font-weight:700;white-space:nowrap;}
  .total-line{
    display:flex;justify-content:space-between;margin-top:12px;padding-top:12px;
    border-top:1px solid var(--line);font-family:'Space Mono',monospace;font-weight:700;
  }
  .empty-note{font-size:13px;opacity:.5;font-style:italic;}

  /* home page cards */
  .grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:18px;
  }
  @media (max-width:600px){.grid{grid-template-columns:1fr;}}
  .card{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:4px;
    padding:24px;
    display:block;
    transition:border-color .15s, transform .1s;
  }
  .card:hover{border-color:var(--turmeric); transform:translateY(-2px);}
  .card .num{
    font-family:'Space Mono',monospace;
    color:var(--turmeric);
    font-size:12px;
    margin-bottom:10px;
  }
  .card h3{font-family:'Fraunces',serif;font-size:20px;margin:0 0 8px;}
  .card p{font-size:13px;opacity:.65;margin:0;line-height:1.5;}
</style>
</head>
<body>

<div class="topbar">
  <a href="{{ url('/dashboard') }}" class="brand">Rashoighor <span>Ledger</span></a>
  <nav class="links">
    <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Home</a>
    <a href="{{ url('/dashboard/foods') }}" class="{{ request()->is('dashboard/foods') ? 'active' : '' }}">Foods</a>
    <a href="{{ url('/dashboard/meal-planner') }}" class="{{ request()->is('dashboard/meal-planner') ? 'active' : '' }}">Meal Planner</a>
    <a href="{{ url('/dashboard/budget-meal') }}" class="{{ request()->is('dashboard/budget-meal') ? 'active' : '' }}">Budget Meal</a>
  </nav>
  <div class="login-widget">
    <span class="token-dot" id="tokenDot"></span>
    <input type="email" id="email" value="test@test.com" placeholder="email">
    <input type="password" id="password" value="password123" placeholder="password">
    <button onclick="login()">Log in</button>
  </div>
</div>

<header class="page-head">
  <div class="eyebrow">@yield('eyebrow', 'Feature Ledger')</div>
  <h1>@yield('heading', 'Rashoighor Ledger')</h1>
  <div class="sub">@yield('sub', '')</div>
</header>

<main>
  @yield('content')
</main>

<script>
const BASE = window.location.origin + window.location.pathname.replace(/\/dashboard.*/, '');

function getToken(){ return localStorage.getItem('rl_token') || ''; }
function setToken(t){ localStorage.setItem('rl_token', t); updateTokenDot(); }
function updateTokenDot(){
  document.getElementById('tokenDot').classList.toggle('on', !!getToken());
}
updateTokenDot();

async function login(){
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;
  try{
    const res = await fetch(BASE + '/api/login', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({email, password})
    });
    const data = await res.json();
    if(data.token){
      setToken(data.token);
      alert('Logged in — token saved for this browser.');
    } else {
      alert(data.message || 'Login failed');
    }
  }catch(e){
    alert('Could not reach server: ' + e.message);
  }
}

function setStatus(id, text, ok){
  const el = document.getElementById(id);
  if(!el) return;
  el.textContent = text;
  el.className = 'status ' + (ok ? 'ok' : 'err');
}
</script>

@yield('scripts')

</body>
</html>
