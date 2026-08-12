@extends('dashboard.layout')

@section('title', 'Budget Meal — Rashoighor Ledger')
@section('eyebrow', 'Feature 03')
@section('heading', 'Budget Meal Recommendation')
@section('sub', 'Give it a budget in taka — get back the best protein-per-taka combo that fits.')

@section('content')
<div class="stall">
  <h2>Set a budget</h2>
  <p class="desc">No login needed for this one.</p>
  <div>
    <input class="field" type="number" id="budget" value="50" placeholder="budget (৳)">
  </div>
  <button class="action" onclick="getBudgetMeal()">Find a combo</button>
  <div class="status" id="budgetStatus"></div>
  <div class="receipt" id="budgetReceipt"></div>
</div>
@endsection

@section('scripts')
<script>
async function getBudgetMeal(){
  const budget = Number(document.getElementById('budget').value);
  try{
    const res = await fetch(BASE + '/api/budget-meal/recommend', {
      method: 'POST',
      headers: {'Content-Type':'application/json'},
      body: JSON.stringify({budget})
    });
    const data = await res.json();
    const box = document.getElementById('budgetReceipt');
    if(data.recommended_items){
      if(data.recommended_items.length === 0){
        box.innerHTML = '<div class="empty-note">Nothing fits that budget yet.</div>';
      } else {
        box.innerHTML = data.recommended_items.map(f => `
          <div class="food-row">
            <div>
              <div class="food-name">${f.name}</div>
              <div class="food-meta">${f.protein_g}g protein · value score ${f.value_score.toFixed(2)}</div>
            </div>
            <div class="price-tag">৳${f.price_per_100g}</div>
          </div>
        `).join('') + `
          <div class="total-line"><span>Total spent</span><span>৳${data.total_cost} of ৳${data.budget}</span></div>
        `;
      }
      box.classList.add('show');
      setStatus('budgetStatus', 'Combo ready', true);
    } else {
      setStatus('budgetStatus', data.message || 'Could not fetch recommendation', false);
    }
  }catch(e){
    setStatus('budgetStatus', 'Error: ' + e.message, false);
  }
}
</script>
@endsection
