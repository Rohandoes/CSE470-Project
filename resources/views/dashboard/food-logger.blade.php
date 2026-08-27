@extends('dashboard.layout')

@section('title', 'Food Logger — Vitality')
@section('eyebrow', 'Feature 05')
@section('heading', 'AI food logger')
@section('sub', 'Type what you ate in plain language. Date and meal time are detected automatically.')

@section('content')
<div class="stall">
  <h2>Log a meal</h2>
  <p class="desc" id="autoTimeNote">Detecting today's date and meal time…</p>

  <div style="margin-bottom:14px;">
    <textarea
      id="logText"
      placeholder="I had 2 rotis and a bowl of dal"
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
  <a href="{{ url('/dashboard/food-history') }}" class="action" style="background:transparent;color:var(--mint);border:1px solid var(--mint);margin-left:10px;text-decoration:none;display:inline-block;">View history</a>

  <div class="status" id="logStatus"></div>
  <div class="receipt" id="logReceipt"></div>
  <div id="aiReplyNote" style="margin-top:12px;font-size:14px;color:var(--mint);display:none;background:var(--bg-panel-2);padding:12px 14px;border-radius:10px;"></div>
  <div id="unmatchedNote" style="margin-top:10px;font-size:13px;display:none;"></div>
</div>
@endsection

@section('scripts')
<script>
function slotFromHour(h){
  if(h >= 5 && h < 11) return 'breakfast';
  if(h >= 11 && h < 16) return 'lunch';
  if(h >= 16 && h < 22) return 'dinner';
  return 'snack';
}

(function showAutoTime(){
  const now = new Date();
  const slot = slotFromHour(now.getHours());
  const dateStr = now.toLocaleDateString(undefined, { weekday:'long', month:'short', day:'numeric' });
  const timeStr = now.toLocaleTimeString(undefined, { hour:'2-digit', minute:'2-digit' });
  document.getElementById('autoTimeNote').textContent = `Logging as ${slot} · ${dateStr}, ${timeStr}`;
})();

async function logFood(){
  const token = getToken();
  if(!token){ setStatus('logStatus', 'Log in first', false); return; }

  const text = document.getElementById('logText').value.trim();
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
      body: JSON.stringify({ text })
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

      const replyBox = document.getElementById('aiReplyNote');
      if(data.ai_reply){
        replyBox.style.display = 'block';
        replyBox.textContent = data.ai_reply;
      } else {
        replyBox.style.display = 'none';
      }

      if(data.newly_added_foods && data.newly_added_foods.length > 0){
        unmatchedBox.style.display = 'block';
        unmatchedBox.style.color = 'var(--mint)';
        unmatchedBox.textContent = 'New to the database: ' + data.newly_added_foods.join(', ');
      } else {
        unmatchedBox.style.display = 'none';
      }

      setStatus('logStatus', 'Logged as ' + data.log.meal_slot, true);
      document.getElementById('logText').value = '';
    } else {
      setStatus('logStatus', data.message || 'Could not log this', false);
    }
  }catch(e){
    setStatus('logStatus', 'Error: ' + e.message, false);
  }
}
</script>
@endsection
