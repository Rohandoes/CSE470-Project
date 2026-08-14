@extends('dashboard.layout')

@section('title', 'Weekly Grocery — Better-Everyday')
@section('eyebrow', 'Feature 04')
@section('heading', 'Weekly Grocery Budget')
@section('sub', 'Give it a weekly budget in taka — get back a 7-day plan, three meals a day, split evenly with variety across days.')

@section('content')
<div class="stall">
  <h2>Set a weekly budget</h2>
  <p class="desc">No login needed for this one.</p>
  <div>
    <input class="field" type="number" id="weeklyBudget" value="700" placeholder="weekly budget (৳)">
  </div>
  <button class="action" onclick="getWeeklyPlan()">Build my week</button>
  <div class="status" id="weekStatus"></div>
</div>

<div class="stall" id="weekResultStall" style="display:none;">
  <h2>7-day plan</h2>
  <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:18px;" id="dayTabs"></div>
  <div id="dayContent"></div>
  <div class="total-line" id="weekTotalLine" style="margin-top:18px;"></div>
</div>
@endsection

@section('scripts')
<script>
let weekData = null;
let activeDay = 1;

async function getWeeklyPlan(){
  const weekly_budget = Number(document.getElementById('weeklyBudget').value);
  try{
    const res = await fetch(BASE + '/api/weekly-grocery/recommend', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({weekly_budget})
    });
    const data = await res.json();
    if(!data.days){
      setStatus('weekStatus', data.message || 'Could not build a plan', false);
      return;
    }
    weekData = data;
    activeDay = 1;
    renderTabs();
    renderDay(1);
    document.getElementById('weekResultStall').style.display = 'block';
    document.getElementById('weekTotalLine').innerHTML =
      `<span>Week total</span><span>৳${data.total_cost} of ৳${data.weekly_budget} · ৳${data.daily_budget}/day</span>`;
    setStatus('weekStatus', 'Plan ready', true);
  }catch(e){
    setStatus('weekStatus', 'Error: ' + e.message, false);
  }
}

function renderTabs(){
  const tabs = document.getElementById('dayTabs');
  tabs.innerHTML = weekData.days.map(d => `
    <button
      onclick="renderDay(${d.day})"
      style="
        background:${d.day === activeDay ? 'var(--turmeric)' : 'transparent'};
        color:${d.day === activeDay ? 'var(--bg)' : 'var(--ink)'};
        border:1px solid var(--turmeric);
        font-family:'Space Mono',monospace;
        font-size:12px;
        padding:8px 14px;
        border-radius:3px;
        cursor:pointer;
      "
    >Day ${d.day}</button>
  `).join('');
}

function renderDay(dayNum){
  activeDay = dayNum;
  renderTabs();
  const day = weekData.days.find(d => d.day === dayNum);
  const slots = ['breakfast', 'lunch', 'dinner'];
  const content = document.getElementById('dayContent');
  content.innerHTML = slots.map(slot => {
    const meal = day.meals[slot];
    const itemsHtml = meal.items.length
      ? meal.items.map(f => `
          <div class="item-row">
            <span>${f.name}</span>
            <span class="food-meta">${f.protein_g}g protein</span>
          </div>
        `).join('')
      : '<div class="empty-note">Nothing fit this slot.</div>';
    return `
      <div style="margin-bottom:16px;">
        <div style="display:flex;justify-content:space-between;align-items:baseline;margin-bottom:4px;">
          <span style="font-family:'Fraunces',serif;font-weight:600;text-transform:capitalize;">${slot}</span>
          <span class="price-tag">৳${meal.cost}</span>
        </div>
        ${itemsHtml}
      </div>
    `;
  }).join('') + `
    <div class="item-row" style="border-top:1px solid var(--line);padding-top:10px;font-weight:700;">
      <span>Day ${dayNum} total</span>
      <span class="price-tag">৳${day.day_total}</span>
    </div>
  `;
}
</script>
@endsection
