@extends('layouts.app')

@section('title')
<p style="font-weight: 400; color:rgba(255,255,255,0.5);">Pages <span style="color:#fff">/ Result</span></p>
<p>Scrape - Retrain Results</p>
@endsection

@section('content')
<style>
    .container {
        width: 90%;
        margin: 20px auto;
        font-family: Arial, sans-serif;
    }
    h2 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }
    .card {
        border: 1px solid #ddd;
        border-radius: 5px;
        margin-bottom: 20px;
        box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
    }
    .card-header {
        font-size: 18px;
        font-weight: bold;
        color: white;
        border-bottom: 1px solid #ddd;
    }
    .card-body {
        padding: 15px;
    }
    .btn {
        padding: 6px 12px;
        color: white;
        background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179));
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-size: 14px;
        text-decoration: none;
    }
</style>

<div class="container">
    <div class="card">
        <div class="card-header">Retrain</div>
        <div class="card-body">
            <h2>Retrain success</h2>
            <a href="{{route('scrape.index')}}" class="btn">Back</a>
        </div>
    </div>
</div>
@endsection
