@extends('dashboard.layout')

@section('title', 'Profile — Vitality')
@section('eyebrow', 'Your profile')
@section('heading', 'Health profile')
@section('sub', 'This helps the AI give you advice that actually fits you — age, size, and goals.')

@section('content')
<div class="stall">
  <h2>Your details</h2>
  <p class="desc">BMI is calculated automatically from height and weight.</p>

  <div style="display:flex;flex-wrap:wrap;gap:0 10px;">
    <div>
      <label style="display:block;font-size:12px;color:var(--ink-dim);margin-bottom:6px;">Age</label>
      <input class="field" type="number" id="age" placeholder="e.g. 21">
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--ink-dim);margin-bottom:6px;">Gender</label>
      <select class="field" id="gender">
        <option value="">—</option>
        <option value="male">Male</option>
        <option value="female">Female</option>
        <option value="other">Other</option>
      </select>
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--ink-dim);margin-bottom:6px;">Height (cm)</label>
      <input class="field" type="number" id="height_cm" placeholder="e.g. 170">
    </div>
    <div>
      <label style="display:block;font-size:12px;color:var(--ink-dim);margin-bottom:6px;">Weight (kg)</label>
      <input class="field" type="number" id="weight_kg" placeholder="e.g. 65">
    </div>
  </div>

  <div style="margin-bottom:12px;">
    <label style="display:block;font-size:12px;color:var(--ink-dim);margin-bottom:6px;">Activity level</label>
    <select class="field" id="activity_level" style="width:260px;">
      <option value="">—</option>
      <option value="sedentary">Sedentary (little exercise)</option>
      <option value="light">Lightly active</option>
      <option value="moderate">Moderately active</option>
      <option value="active">Very active</option>
    </select>
  </div>

  <div style="margin-bottom:16px;">
    <label style="display:block;font-size:12px;color:var(--ink-dim);margin-bottom:6px;">Goal</label>
    <select class="field" id="goal" style="width:260px;">
      <option value="">—</option>
      <option value="lose weight">Lose weight</option>
      <option value="maintain">Maintain</option>
      <option value="gain muscle">Gain muscle</option>
      <option value="eat healthier">Eat healthier</option>
    </select>
  </div>

  <button class="action" onclick="saveProfile()">Save profile</button>
  <div class="status" id="profileStatus"></div>

  <div id="bmiDisplay" style="margin-top:20px;padding:16px;background:var(--bg-panel-2);border-radius:12px;display:none;">
    <div style="font-size:12px;color:var(--ink-dim);margin-bottom:4px;">Your BMI</div>
    <div style="font-size:28px;font-weight:700;color:var(--mint);" id="bmiValue"></div>
  </div>
</div>
@endsection

@section('scripts')
<script>
async function loadProfile(){
  const token = getToken();
  if(!token) return;
  try{
    const res = await fetch(BASE + '/api/profile', {
      headers: { 'Authorization': 'Bearer ' + token }
    });
    const data = await res.json();
    if(data.age) document.getElementById('age').value = data.age;
    if(data.gender) document.getElementById('gender').value = data.gender;
    if(data.height_cm) document.getElementById('height_cm').value = data.height_cm;
    if(data.weight_kg) document.getElementById('weight_kg').value = data.weight_kg;
    if(data.activity_level) document.getElementById('activity_level').value = data.activity_level;
    if(data.goal) document.getElementById('goal').value = data.goal;
    if(data.bmi){
      document.getElementById('bmiDisplay').style.display = 'block';
      document.getElementById('bmiValue').textContent = data.bmi;
    }
  }catch(e){}
}

async function saveProfile(){
  const token = getToken();
  if(!token){ setStatus('profileStatus', 'Log in first', false); return; }

  const payload = {
    age: document.getElementById('age').value || null,
    gender: document.getElementById('gender').value || null,
    height_cm: document.getElementById('height_cm').value || null,
    weight_kg: document.getElementById('weight_kg').value || null,
    activity_level: document.getElementById('activity_level').value || null,
    goal: document.getElementById('goal').value || null,
  };

  try{
    const res = await fetch(BASE + '/api/profile', {
      method: 'PUT',
      headers: {
        'Content-Type':'application/json',
        'Authorization': 'Bearer ' + token
      },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if(data.message){
      setStatus('profileStatus', 'Saved', true);
      if(data.bmi){
        document.getElementById('bmiDisplay').style.display = 'block';
        document.getElementById('bmiValue').textContent = data.bmi;
      }
    } else {
      setStatus('profileStatus', 'Could not save', false);
    }
  }catch(e){
    setStatus('profileStatus', 'Error: ' + e.message, false);
  }
}

loadProfile();
</script>
@endsection
