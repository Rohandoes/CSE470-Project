@extends('dashboard.layout')

@section('title', 'Log History — Vitality')
@section('eyebrow', 'Feature 05')
@section('heading', 'Log history')
@section('sub', 'Everything you have logged so far, most recent first.')

@section('content')
<div class="stall">
  <button class="action" style="background:transparent;color:var(--mint);border:1px solid var(--mint);" onclick="loadHistory()">Refresh</button>
  <div id="historyList" style="margin-top:18px;"></div>
</div>
@endsection

@section('scripts')
<script>
const slotColors = {
  breakfast: '#F5C451',
  lunch: '#34D399',
  dinner: '#FF6B4A',
  snack: '#9AA1AC',
};

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
      list.innerHTML = '<div class="empty-note">No logs yet. Head to the Food Logger to add one.</div>';
      return;
    }

    list.innerHTML = logs.map(log => {
      const totalKcal = log.items.reduce((sum, i) => sum + Math.round(i.food.calories_per_100g * i.quantity_g / 100), 0);
      const color = slotColors[log.meal_slot] || '#9AA1AC';
      return `
        <div style="border-bottom:1px solid var(--line);padding:16px 0;">
          <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
            <div style="display:flex;align-items:center;gap:8px;">
              <span style="background:${color};color:#0F1115;font-size:11px;font-weight:700;padding:3px 10px;border-radius:100px;text-transform:capitalize;">${log.meal_slot || 'log'}</span>
              <span style="font-weight:600;">${log.log_date}</span>
            </div>
            <span class="food-meta">${totalKcal} kcal total</span>
          </div>
          <div style="font-size:13px;color:var(--ink-dim);margin-bottom:8px;font-style:italic;">"${log.raw_text}"</div>
          ${log.ai_reply ? `<div style="font-size:13px;color:var(--mint);margin-bottom:8px;">${log.ai_reply}</div>` : ''}
          <div style="display:flex;flex-wrap:wrap;gap:6px;">
            ${log.items.map(i => `
              <span style="background:var(--bg-panel-2);padding:4px 10px;border-radius:100px;font-size:12px;">
                ${i.food.name} · ${i.quantity_g}g
              </span>
            `).join('')}
          </div>
        </div>
      `;
    }).join('');
  }catch(e){
    document.getElementById('historyList').innerHTML = '<div class="empty-note">Could not load history.</div>';
  }
}

loadHistory();
</script>
@endsection
