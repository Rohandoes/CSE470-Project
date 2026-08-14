@extends('dashboard.layout')

@section('title', 'Rashoighor Ledger — Home')
@section('eyebrow', 'Zobairul · Health App')
@section('heading', 'Pick a stall')
@section('sub', 'Four features, four pages. Log in up top first — the token carries over to whichever page you open next.')

@section('content')
<div class="grid">

  <a href="{{ url('/dashboard/foods') }}" class="card">
    <div class="num">01</div>
    <h3>Bangladeshi Food Database</h3>
    <p>Browse everything currently stocked — name, category, calories, protein, and price per 100g.</p>
  </a>

  <a href="{{ url('/dashboard/meal-planner') }}" class="card">
    <div class="num">02</div>
    <h3>Meal Planner</h3>
    <p>Log a meal slot for a given date, built from foods in the database. Requires login.</p>
  </a>

  <a href="{{ url('/dashboard/budget-meal') }}" class="card">
    <div class="num">03</div>
    <h3>Budget Meal Recommendation</h3>
    <p>Hand it a budget in taka, get back the best protein-per-taka combo that fits under it.</p>
  </a>

  <a href="{{ url('/dashboard/weekly-grocery') }}" class="card">
    <div class="num">04</div>
    <h3>Weekly Grocery Budget</h3>
    <p>Give it a weekly budget, get a full 7-day plan — three meals a day, with variety.</p>
  </a>

  <a href="{{ url('/dashboard/food-logger') }}" class="card">
    <div class="num">05</div>
    <h3>AI Food Logger</h3>
    <p>Type what you ate in plain language — it matches foods automatically and logs it.</p>
</a>

</div>
@endsection
