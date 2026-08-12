@extends('dashboard.layout')

@section('title', 'Meal Planner — Rashoighor Ledger')
@section('eyebrow', 'Feature 02')
@section('heading', 'Meal Planner')
@section('sub', 'Log a lunch slot using foods from the database. Needs you logged in up top.')

@section('content')
<div class="stall">
  <h2>New meal plan</h2>
  <p class="desc">Demo uses food IDs 1 and 2 (150g and 200g). Swap the date and send.</p>
  <div>
    <input class="field" type="date" id="planDate" value="2026-08-15">
  </div>
  <button class="action" onclick="createMealPlan()">Create meal plan</button>
  <div class="status" id="mealStatus"></div>
  <div class="receipt" id="mealReceipt"></div>
</div>
@endsection

@section('scripts')
<script>
async function createMealPlan(){
  const token = getToken();
  if(!token){ setStatus('mealStatus', 'Log in up top first', false); return; }
  const plan_date = document.getElementById('planDate').value;
  try{
    const res = await fetch(BASE + '/api/meal-plans', {
      method: 'POST',
      headers: {
        'Content-Type':'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify({
        plan_date,
        meal_slot: 'lunch',
        items: [
          { food_id: 1, quantity_g: 150 },
          { food_id: 2, quantity_g: 200 }
        ]
      })
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
</script>
@endsection
