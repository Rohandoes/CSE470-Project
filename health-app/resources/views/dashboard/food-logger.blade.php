@extends('dashboard.layout')

@section('title', 'Food Logger — Better-Everyday')
@section('eyebrow', 'Feature 05')
@section('heading', 'AI food logger')
@section('sub', 'Type what you ate in plain language — it matches foods from the database automatically.')

@section('content')
<div class="stall">
  <h2>Log a meal</h2>
  <p class="desc">Try something like "2 rotis and a bowl of dal for lunch". Requires login.</p>
  <div>
    <input class="field" type="date" id="logDate" value="2026-08-15" style="width:160px;">
  </div>
  <div style="margin-bottom:14px;">
    <textarea
      id="logText"
      placeholder="I had 2 rotis and a bowl of dal for lunch"
      style="
        width:100%;
        background:var(--bg-panel-2);
        border:1px solid var(--line);
        color:var(--ink);
        font-family:'Inter',sans-serif;
        font-size:14px;
        padding:12px 14px;
        border-radius:10px;
        outline:none;
        min-height:80px;
        resize:vertical;
      "
    ></textarea>
  </div>
  <button class="action" onclick="logFood()">Log it</button>
  <div class="status" id="logStatus"></div>
  <div class="receipt" id="logReceipt"></div>
  <div id="unmatchedNote" style="margin-top:10px;font-size:13px;color:var(--coral);display:none;"></div>
</div>

<div class="stall">
  <h2>Recent logs</h2>
  <p class="desc">Everything you've logged so far.</p>
  <button class="action" style="background:transparent;color:var(--mint);border:1px solid var(--mint);" onclick="loadHistory()">Refresh history</button>
  <div id="historyList" style="margin-top:16px;"></div>
</div>
@endsection

@section('scripts')
<script>
async function logFood(){
  const token = getToken();
  if(!token){ setStatus('logStatus', 'Log in first', false); return; }

  const text = document.getElementById('logText').value.trim();
  const log_date = document.getElementById('logDate').value;

  if(!text){
    setStatus('logStatus', 'Type what you ate first', false);
    return;
  }

  try{
    const res = await fetch(BASE + '/api/food-logs', {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({ text, log_date })
    });
    const data = await res.json();
    const box = document.getElementById('logReceipt');
    const unmatchedBox = document.getElementById('unmatchedNote');

    if(data.log && data.log.items){
      if(data.log.items.length === 0){
        box.innerHTML = '<div class="empty-note">Nothing matched. Try simpler wording.</div>';
      } else {
        box.innerHTML = data.log.items.map(i => `
          <div class="item-row">
            <span>${i.food.name}</span>
            <span class="food-meta">${i.quantity_g}g · ${Math.round(i.food.calories_per_100g * i.quantity_g / 100)} kcal</span>
          </div>
        `).join('');
      }
      box.classList.add('show');

      if(data.unmatched && data.unmatched.length > 0){
        unmatchedBox.style.display = 'block';
        unmatchedBox.textContent = "Couldn't match: " + data.unmatched.join(', ');
      } else {
        unmatchedBox.style.display = 'none';
      }

      setStatus('logStatus', 'Logged', true);
      document.getElementById('logText').value = '';
      loadHistory();
    } else {
      setStatus('logStatus', data.message || 'Could not log this', false);
    }
  }catch(e){
    setStatus('logStatus', 'Error: ' + e.message, false);
  }
}

async function loadHistory(){
  const token = getToken();
  if(!token) return;

  try{
    const res = await fetch(BASE + '/api/food-logs', {
      headers: { 'Authorization': 'Bearer ' + token }
    });
    const logs = await res.json();
    const list = document.getElementById('historyList');

    if(!Array.isArray(logs) || logs.length === 0){
      list.innerHTML = '<div class="empty-note">No logs yet.</div>';
      return;
    }

    list.innerHTML = logs.map(log => `
      <div style="border-bottom:1px solid var(--line);padding:12px 0;">
        <div style="display:flex;justify-content:space-between;margin-bottom:6px;">
          <span style="font-weight:600;">${log.log_date}</span>
          <span class="food-meta">${log.items.length} item(s)</span>
        </div>
        <div style="font-size:13px;color:var(--ink-dim);margin-bottom:6px;">"${log.raw_text}"</div>
        <div style="display:flex;flex-wrap:wrap;gap:6px;">
          ${log.items.map(i => `
            <span style="background:var(--bg-panel-2);padding:4px 10px;border-radius:100px;font-size:12px;">
              ${i.food.name} · ${i.quantity_g}g
            </span>
          `).join('')}
        </div>
      </div>
    `).join('');
  }catch(e){
    document.getElementById('historyList').innerHTML = '<div class="empty-note">Could not load history.</div>';
  }
}

loadHistory();
</script>
@endsection
