<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Log in — Vitality</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --bg:#0F1115;
    --bg-panel:#181B21;
    --bg-panel-2:#1F232B;
    --mint:#34D399;
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
    min-height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:24px;
  }
  .panel{
    width:100%;
    max-width:380px;
    background:var(--bg-panel);
    border:1px solid var(--line);
    border-radius:20px;
    padding:40px 32px;
  }
  .brand{
    display:flex;
    align-items:center;
    gap:8px;
    font-family:'Poppins',sans-serif;
    font-weight:700;
    font-size:20px;
    margin-bottom:6px;
  }
  .brand .dot{width:11px;height:11px;border-radius:50%;background:var(--mint);}
  .tagline{color:var(--ink-dim);font-size:14px;margin-bottom:32px;}
  label{
    display:block;
    font-size:12px;
    font-weight:600;
    color:var(--ink-dim);
    text-transform:uppercase;
    letter-spacing:.06em;
    margin-bottom:8px;
  }
  input{
    width:100%;
    background:var(--bg-panel-2);
    border:1px solid var(--line);
    color:var(--ink);
    font-family:'Inter',sans-serif;
    font-size:15px;
    padding:13px 14px;
    border-radius:12px;
    outline:none;
    margin-bottom:20px;
    transition:border-color .15s;
  }
  input:focus{border-color:var(--mint);}
  button{
    width:100%;
    background:var(--mint);
    color:#06281C;
    border:none;
    font-family:'Inter',sans-serif;
    font-weight:700;
    font-size:15px;
    padding:14px;
    border-radius:12px;
    cursor:pointer;
    transition:transform .1s ease, background .15s ease;
  }
  button:hover{background:#4EE3A8; transform:translateY(-1px);}
  button:active{transform:translateY(0);}
  .status{
    font-size:13px;
    margin-top:14px;
    text-align:center;
    min-height:16px;
    font-weight:500;
  }
  .status.err{color:var(--coral);}
  .status.ok{color:var(--mint);}
</style>
</head>
<body>

<div class="panel">
  <div class="brand"><span class="dot"></span>Vitality</div>
  <div class="tagline">Log in to plan meals, set budgets, and track your food.</div>

  <label for="email">Email</label>
  <input type="email" id="email" value="test@test.com" placeholder="name@example.com">

  <label for="password">Password</label>
  <input type="password" id="password" value="password123" placeholder="Enter your password">

  <button onclick="login()">Log in</button>
  <div class="status" id="loginStatus"></div>
</div>

<script>
const BASE = window.location.origin + window.location.pathname.replace(/\/dashboard.*/, '');

if(localStorage.getItem('rl_token')){
  window.location.href = BASE + '/dashboard';
}

async function login(){
  const email = document.getElementById('email').value.trim();
  const password = document.getElementById('password').value;
  const statusEl = document.getElementById('loginStatus');

  if(!email || !password){
    statusEl.textContent = 'Enter your email and password.';
    statusEl.className = 'status err';
    return;
  }

  try{
    const res = await fetch(BASE + '/api/login', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({email, password})
    });
    const data = await res.json();
    if(data.token){
      localStorage.setItem('rl_token', data.token);
      statusEl.textContent = 'Logged in — redirecting.';
      statusEl.className = 'status ok';
      window.location.href = BASE + '/dashboard';
    } else {
      statusEl.textContent = data.message || 'Invalid email or password.';
      statusEl.className = 'status err';
    }
  }catch(e){
    statusEl.textContent = 'Could not reach the server.';
    statusEl.className = 'status err';
  }
}

document.getElementById('password').addEventListener('keydown', function(e){
  if(e.key === 'Enter') login();
});
</script>

</body>
</html>
