@extends('dashboard.layout')

@section('title', 'Health App — Better-Everyday')
@section('eyebrow', 'Feature 01')
@section('heading', 'Bangladeshi Food Database')
@section('sub', 'Every item currently sitting in the foods table.')

@section('content')
<div class="stall">
  <h2>Stock list</h2>
  <p class="desc">Pulls live from <span style="font-family:'Space Mono',monospace;opacity:.8;">/api/foods</span>.</p>
  <button class="action" onclick="getFoods()">Load foods</button>
  <div class="status" id="foodsStatus"></div>
  <div class="receipt" id="foodsReceipt"></div>
</div>
@endsection

@section('scripts')
<script>
async function getFoods(){
  try{
    const res = await fetch(BASE + '/api/foods');
    const data = await res.json();
    const box = document.getElementById('foodsReceipt');
    if(!Array.isArray(data) || data.length === 0){
      box.innerHTML = '<div class="empty-note">No foods stocked yet.</div>';
    } else {
      box.innerHTML = data.map(f => `
        <div class="food-row">
          <div>
            <div class="food-name">${f.name}</div>
            <div class="food-meta">${f.category} · ${f.calories_per_100g} kcal/100g · ${f.protein_g}g protein</div>
          </div>
          <div class="price-tag">${f.price_per_100g != null ? '৳' + f.price_per_100g + '/100g' : '—'}</div>
        </div>
      `).join('');
    }
    box.classList.add('show');
    setStatus('foodsStatus', data.length + ' item(s) loaded', true);
  }catch(e){
    setStatus('foodsStatus', 'Error: ' + e.message, false);
  }
}
// auto-load on page open
getFoods();
</script>
@endsection
