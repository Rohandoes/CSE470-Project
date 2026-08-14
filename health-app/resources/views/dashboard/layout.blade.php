<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>@yield('title', 'Better-Everyday')</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#0F1115;
    --bg-panel:#181B21;
    --bg-panel-2:#1F232B;
    --mint:#34D399;
    --mint-dark:#0F1115;
    --coral:#FF6B4A;
    --ink:#F3F4F6;
    --ink-dim:#9AA1AC;
    --line: rgba(255,255,255,0.08);
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

  .topbar{
    border-bottom:1px solid var(--line);
    padding:20px 24px;
    display:flex;
    align-items:center;
    justify-content:space-between;
    max-width:920px;
    margin:0 auto;
    flex-wrap:wrap;
    gap:12px;
  }
  .brand{
    font-family:'Poppins',sans-serif;
    font-weight:700;
    font-size:19px;
    letter-spacing:-.01em;
    display:flex;
    align-items:center;
    gap:8px;
  }
  .brand .dot{width:10px;height:10px;border-radius:50%;background:var(--mint);display:inline-block;}
  nav.links{
    display:flex;
    gap:22px;
    font-size:14px;
    font-weight:500;
  }
  nav.links a{
    color:var(--ink-dim);
    padding-bottom:4px;
    border-bottom:2px solid transparent;
    transition:color .15s, border-color .15s;
  }
  nav.links a:hover{color:var(--ink);}
  nav.links a.active{color:var(--mint);border-color:var(--mint);}

  .user-pill{
    display:flex;align-items:center;gap:8px;
    background:var(--bg-panel);
    border:1px solid var(--line);
    padding:6px 14px 6px 8px;
    border-radius:100px;
    font-size:13px;
  }
  .user-pill .avatar{
    width:22px;height:22px;border-radius:50%;
    background:var(--mint);
    color:#06281C;
    font-weight:700;
    font-size:11px;
    display:flex;align-items:center;justify-content:center;
  }
  .user-pill button{
    background:none;border:none;color:var(--ink-dim);
    font-size:12px;cursor:pointer;padding:0;margin-left:6px;
    font-family:'Inter',sans-serif;
  }
  .user-pill button:hover{color:var(--coral);}

  header.page-head{
    padding:52px 24px 32px;
    max-width:920px;
    margin:0 auto;
  }
  .eyebrow{
    text-transform:uppercase;
    letter-spacing:.12em;
    font-size:12px;
    font-weight:600;
    color:var(--mint);
    margin-bottom:14px;
  }
  h1{
    font-family:'Poppins',sans-serif;
    font-weight:700;
    font-size:36px;
    line-height:1.1;
    margin:0 0 12px;
    letter-spacing:-.01em;
  }
  .sub{color:var(--ink-dim);font-size:16px;max-width:560px;line-height:1.55;}

  main{
    max-width:920px;
    margin:0 auto;
    padding:8px 24px 0;
    display:flex;
    flex-direction:column;
    gap:24px;
  }
  .stall{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:16px;
    padding:28px;
    position:relative;
  }
  .stall h2{
    font-family:'Poppins',sans-serif;
    font-size:20px;
    margin:0 0 6px;
    font-weight:600;
  }
  .stall p.desc{font-size:14px;color:var(--ink-dim);margin:0 0 18px;line-height:1.5;}

  input.field{
    background:var(--bg-panel-2);
    border:1px solid var(--line);
    color:var(--ink);
    font-family:'Inter',sans-serif;
    font-size:14px;
    padding:11px 14px;
    width:220px;
    margin:0 10px 12px 0;
    outline:none;
    border-radius:10px;
    transition:border-color .15s;
  }
  input.field:focus{border-color:var(--mint);}
  select.field{
    background:var(--bg-panel-2);
    border:1px solid var(--line);
    color:var(--ink);
    font-family:'Inter',sans-serif;
    font-size:14px;
    padding:11px 14px;
    margin:0 10px 12px 0;
    outline:none;
    border-radius:10px;
  }
  button.action{
    background:var(--mint);
    color:#06281C;
    border:none;
    font-family:'Inter',sans-serif;
    font-weight:600;
    font-size:14px;
    padding:12px 22px;
    border-radius:10px;
    cursor:pointer;
    transition:transform .1s ease, background .15s ease;
  }
  button.action:hover{background:#4EE3A8; transform:translateY(-1px);}
  button.action:active{transform:translateY(0);}

  .status{font-size:13px;margin-top:10px;min-height:16px;font-weight:500;}
  .status.ok{color:var(--mint);}
  .status.err{color:var(--coral);}

  .receipt{margin-top:16px;border-top:1px solid var(--line);padding-top:14px;display:none;}
  .receipt.show{display:block;}
  .food-row, .item-row{
    display:flex;justify-content:space-between;align-items:baseline;
    padding:10px 0;border-bottom:1px solid var(--line);font-size:14px;
  }
  .food-row:last-child, .item-row:last-child{border-bottom:none;}
  .food-name{font-weight:600;}
  .food-meta{font-size:12px;color:var(--ink-dim);}
  .price-tag{color:var(--mint);font-weight:700;white-space:nowrap;}
  .total-line{
    display:flex;justify-content:space-between;margin-top:12px;padding-top:12px;
    border-top:1px solid var(--line);font-weight:700;
  }
  .empty-note{font-size:13px;color:var(--ink-dim);font-style:italic;}

  .grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:16px;
  }
  @media (max-width:600px){.grid{grid-template-columns:1fr;}}
  .card{
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:16px;
    padding:26px;
    display:block;
    transition:border-color .15s, transform .1s;
  }
  .card:hover{border-color:var(--mint); transform:translateY(-2px);}
  .card .num{
    color:var(--mint);
    font-size:12px;
    font-weight:700;
    margin-bottom:10px;
  }
  .card h3{font-family:'Poppins',sans-serif;font-size:19px;margin:0 0 8px;font-weight:600;}
  .card p{font-size:13px;color:var(--ink-dim);margin:0;line-height:1.5;}
</style>
</head>
<body>

<div class="topbar">
  <a href="{{ url('/dashboard') }}" class="brand"><span class="dot"></span>Better-Everyday</a>
  <nav class="links">
    <a href="{{ url('/dashboard') }}" class="{{ request()->is('dashboard') ? 'active' : '' }}">Home</a>
    <a href="{{ url('/dashboard/foods') }}" class="{{ request()->is('dashboard/foods') ? 'active' : '' }}">Foods</a>
    <a href="{{ url('/dashboard/meal-planner') }}" class="{{ request()->is('dashboard/meal-planner') ? 'active' : '' }}">Meal planner</a>
    <a href="{{ url('/dashboard/budget-meal') }}" class="{{ request()->is('dashboard/budget-meal') ? 'active' : '' }}">Budget meal</a>
    <a href="{{ url('/dashboard/weekly-grocery') }}" class="{{ request()->is('dashboard/weekly-grocery') ? 'active' : '' }}">Weekly grocery</a>
    <a href="{{ url('/dashboard/food-logger') }}" class="{{ request()->is('dashboard/food-logger') ? 'active' : '' }}">Food logger</a>
  </nav>
  <div class="user-pill">
    <span class="avatar">Z</span>
    <span>Zobairul</span>
    <button onclick="logout()">Log out</button>
  </div>
</div>

<header class="page-head">
  <div class="eyebrow">@yield('eyebrow', 'Feature')</div>
  <h1>@yield('heading', 'Better-Everyday')</h1>
  <div class="sub">@yield('sub', '')</div>
</header>

<main>
  @yield('content')
</main>

<script>
const BASE = window.location.origin + window.location.pathname.replace(/\/dashboard.*/, '');

function getToken(){ return localStorage.getItem('rl_token') || ''; }
function setToken(t){ localStorage.setItem('rl_token', t); }
function logout(){ localStorage.removeItem('rl_token'); window.location.href = BASE + '/dashboard/login'; }

if(!getToken()){
  window.location.href = BASE + '/dashboard/login';
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
