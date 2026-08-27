@extends('dashboard.layout')

@section('title', 'Foods — Vitality')
@section('eyebrow', 'Feature 01')
@section('heading', 'Food database')
@section('sub', 'Everything currently stocked, filterable by food group.')

@section('content')
<div class="stall" style="margin-bottom:20px;">
  <h2>Filter</h2>
  <p class="desc">Choose a food group to narrow the list, or view everything.</p>
  <select class="field" id="categoryFilter" style="width:220px;" onchange="getFoods()">
    <option value="all">All food groups</option>
    <option value="Grains & Rice">Grains & Rice</option>
    <option value="Protein">Protein</option>
    <option value="Vegetables">Vegetables</option>
    <option value="Fruits">Fruits</option>
    <option value="Dairy">Dairy</option>
    <option value="Snacks">Snacks</option>
  </select>
</div>

<div id="foodsContainer">
  <div class="stall"><p class="desc">Loading foods…</p></div>
</div>
@endsection

@section('scripts')
<script>
const groupMap = {
  'Grains & Rice': ['rice', 'grain'],
  'Protein': ['dal', 'fish', 'meat'],
  'Vegetables': ['vegetable'],
  'Fruits': ['fruit'],
  'Dairy': ['dairy'],
  'Snacks': ['snack'],
};

function renderGroup(groupName, items){
  return `
    <div class="stall" style="margin-bottom:20px;">
      <h2>${groupName}</h2>
      <p class="desc">${items.length} item${items.length !== 1 ? 's' : ''}</p>
      ${items.map(f => `
        <div class="food-row">
          <div>
            <div class="food-name">${f.name}</div>
            <div class="food-meta">${f.calories_per_100g} kcal/100g · ${f.protein_g}g protein · ${f.common_portion ?? ''}</div>
          </div>
          <div class="price-tag">${f.price_per_100g != null ? '৳' + f.price_per_100g + '/100g' : '—'}</div>
        </div>
      `).join('')}
    </div>
  `;
}

async function getFoods(){
  const container = document.getElementById('foodsContainer');
  const filter = document.getElementById('categoryFilter').value;

  try{
    const res = await fetch(BASE + '/api/foods');
    const data = await res.json();

    if(!Array.isArray(data) || data.length === 0){
      container.innerHTML = '<div class="stall"><div class="empty-note">No foods stocked yet.</div></div>';
      return;
    }

    let html = '';

    if(filter === 'all'){
      for(const [groupName, categories] of Object.entries(groupMap)){
        const items = data.filter(f => categories.includes(f.category));
        if(items.length > 0) html += renderGroup(groupName, items);
      }
      const knownCategories = Object.values(groupMap).flat();
      const leftover = data.filter(f => !knownCategories.includes(f.category));
      if(leftover.length > 0) html += renderGroup('Other', leftover);
    } else {
      const categories = groupMap[filter] || [];
      const items = data.filter(f => categories.includes(f.category));
      html = items.length > 0
        ? renderGroup(filter, items)
        : '<div class="stall"><div class="empty-note">Nothing in this group yet.</div></div>';
    }

    container.innerHTML = html;
  }catch(e){
    container.innerHTML = '<div class="stall"><div class="empty-note">Could not load foods.</div></div>';
  }
}

getFoods();
</script>
@endsection
