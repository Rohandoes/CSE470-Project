@extends('dashboard.layout')

@section('title', 'Meal Planner — Better-Everyday')
@section('eyebrow', 'Feature 02')
@section('heading', 'Meal Planner')
@section('sub', 'Log a meal slot using foods from the database. Needs you logged in up top.')

@section('content')
<div class="stall">
  <h2>New meal plan</h2>
  <p class="desc">Pick a date, a slot, and add as many foods as you want.</p>

  <div>
    <input class="field" type="date" id="planDate" value="2026-08-15">
    <select class="field" id="mealSlot" style="width:150px;">
      <option value="breakfast">Breakfast</option>
      <option value="lunch" selected>Lunch</option>
      <option value="dinner">Dinner</option>
      <option value="snack">Snack</option>
    </select>
  </div>

  <div id="itemRows"></div>

  <button class="action" style="background:transparent;color:var(--turmeric);border:1px solid var(--turmeric);margin-right:10px;" onclick="addItemRow()">+ Add food</button>
  <button class="action" onclick="createMealPlan()">Create meal plan</button>

  <div class="status" id="mealStatus"></div>
  <div class="receipt" id="mealReceipt"></div>
</div>
@endsection

@section('scripts')
<script>
let foodsCache = [];
let rowCount = 0;

async function loadFoodsForSelect(){
  try{
    const res = await fetch(BASE + '/api/foods');
    foodsCache = await res.json();
  }catch(e){
    foodsCache = [];
  }
  addItemRow(); // start with one row once foods are loaded
}

function addItemRow(){
  rowCount++;
  const id = 'row' + rowCount;
  const options = foodsCache.map(f => `<option value="${f.id}">${f.name} (৳${f.price_per_100g ?? '—'}/100g)</option>`).join('');
  const wrapper = document.createElement('div');
  wrapper.id = id;
  wrapper.style.cssText = 'display:flex;gap:10px;align-items:center;margin-bottom:10px;';
  wrapper.innerHTML = `
    <select class="field" style="width:260px;margin:0;">${options}</select>
    <input class="field" type="number" placeholder="grams" value="150" style="width:90px;margin:0;">
    <span onclick="document.getElementById('${id}').remove()" style="cursor:pointer;color:var(--chili);font-family:'Space Mono',monospace;font-size:12px;">remove</span>
  `;
  document.getElementById('itemRows').appendChild(wrapper);
}

async function createMealPlan(){
  const token = getToken();
  if(!token){ setStatus('mealStatus', 'Log in up top first', false); return; }

  const plan_date = document.getElementById('planDate').value;
  const meal_slot = document.getElementById('mealSlot').value;

  const rows = document.querySelectorAll('#itemRows > div');
  const items = Array.from(rows).map(row => {
    const select = row.querySelector('select');
    const input = row.querySelector('input');
    return { food_id: Number(select.value), quantity_g: Number(input.value) };
  }).filter(i => i.food_id && i.quantity_g > 0);

  if(items.length === 0){
    setStatus('mealStatus', 'Add at least one food', false);
    return;
  }

  try{
    const res = await fetch(BASE + '/api/meal-plans', {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({ plan_date, meal_slot, items })
    });
    const data = await res.json();
    const box = document.getElementById('mealReceipt');
    if(data.items){
      box.innerHTML = `
        <div class="item-row"><span>Date</span><span class="price-tag">${data.plan_date}</span></div>
        <div class="item-row"><span>Slot</span><span class="price-tag">${data.meal_slot}</span></div>
        ${data.items.map(i => `
          <div class="item-row">
            <span>${i.food.name}</span>
            <span class="food-meta">${i.quantity_g}g</span>
          </div>
        `).join('')}
      `;
      box.classList.add('show');
      setStatus('mealStatus', 'Meal plan #' + data.id + ' created', true);
    } else {
      setStatus('mealStatus', data.message || 'Could not create plan', false);
    }
  }catch(e){
    setStatus('mealStatus', 'Error: ' + e.message, false);
  }
}

loadFoodsForSelect();
</script>
@endsection
