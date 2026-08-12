@extends('dashboard.layout')

@section('title', 'Rashoighor Ledger — Home')
@section('eyebrow', 'Zobairul · Health App')
@section('heading', 'Pick a stall')
@section('sub', 'Three features, three pages. Log in up top first — the token carries over to whichever page you open next.')

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

</div>
@endsection
