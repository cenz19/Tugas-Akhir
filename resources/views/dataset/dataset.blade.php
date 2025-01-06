@extends('layouts.app')

@section('title')
<p style="font-weight: 400; color:rgba(255,255,255,0.5);">Pages <span style="color:#fff">/ Dataset</span></p>
<p>Dataset</p>
@endsection

@section('content')
<div class="container">
    <!-- Table for displaying dataset -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Social Media</th>
                <th>Text</th>
                <th>Time</th>
                <th>Class</th>
            </tr>
        </thead>
        <tbody>
            @php
                $categories = ['non-cyberbullying', 'flaming', 'harassment', 'denigration'];
            @endphp
            @foreach($paginatedDataset as $item)
                <tr>
                    <td>{{ ($paginatedDataset->currentPage() - 1) * $paginatedDataset->perPage() + $loop->iteration }}</td>
                    <td>{{ $item['username'] }}</td>
                    <td>{{ $item['social'] }}</td>
                    <td>{{ $item['text'] }}</td>
                    <td>{{ $item['time'] }}</td>
                    <td>{{ $categories[$item['label']] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination Links -->
    <div class="pagination-links">
        {{ $paginatedDataset->links() }}
    </div>
</div>
<style>
    .table-header{
        display: flex;
        justify-content: space-between;
        align-items:center;
        margin-bottom: 10px;
    }
    .table {
        width: 100%;
        border-collapse: collapse;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        margin-bottom: 12px;
        overflow: hidden;
        font-family: Arial, sans-serif;
    }
    .table thead {
        background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179));
        color: #ffffff;
        text-align: left;
    }

    .table thead th {
        padding: 12px 15px;
        font-weight: 600;
        font-size: 14px;
    }

    /* Table body styling */
    .table tbody tr {
        background-color: #f9f9f9;
    }

    .table tbody tr:nth-child(even) {
        background-color: #e9e9e9; /* Alternate row color */
    }

    /* Table cell padding */
    .table tbody td {
        padding: 10px 15px;
        font-size: 14px;
        color: #333;
    }

    /* "Edit" button styling */
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

    /* "Edit" button hover effect */
    .btn:hover {
        background: linear-gradient(135deg, rgb(15, 37, 80), rgb(70, 118, 189));
    }
    .btn-retrain{
        padding: 10px 20px;
    }
    /* Row hover effect */
    .table tbody tr:hover {
        background-color: #dbeafe;
        cursor: pointer;
    }

    /* Dropdown styling */
    .label-dropdown {
        padding: 6px 12px;
        font-size: 14px;
        border-radius: 4px;
        border: 1px solid #ddd;
        background-color: #ffffff;
        color: #333;
        width: 100%; /* Full width inside the cell */
        box-sizing: border-box;
        transition: border-color 0.3s ease;
        width: 140px;
    }

    /* Dropdown focus styling */
    .label-dropdown:focus {
        border-color: #4d90fe;
        outline: none;
    }

    /* Custom dropdown arrow */
    .label-dropdown option {
        padding: 8px;
    }

    /* Dropdown hover effect */
    .label-dropdown:hover {
        border-color: #4d90fe;
    }

    /* Selected dropdown styling */
    .label-dropdown option:selected {
        background-color: #4d90fe;
        color: white;
    }

    .pagination-links > .flex{
        flex-direction: column;
        gap: 10px;
    }

    .pagination-links > .flex div:first-child{
        gap: 10px;
    }

    .pagination-links > .flex > div:first-child > a, 
    .pagination-links > .flex > div:last-child > div:last-child > span a{
        padding: 6px 12px;
        color: white;
        background: linear-gradient(135deg, rgb(20, 11, 64), rgb(64, 64, 179));
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-size: 14px;
        text-decoration: none;
    }

    .pagination-links > .flex > div:first-child > span, 
    .pagination-links > .flex > div:last-child > div:last-child > span > span{
        padding: 6px 12px;
        color: rgb(118, 118, 118);
        background: linear-gradient(rgb(66, 66, 74), rgb(28, 28, 28));
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background 0.3s ease;
        font-size: 14px;
        text-decoration: none;
    }

    .pagination-links > .flex > div:first-child > a:hover, 
    .pagination-links > .flex > div:last-child > div:last-child > span a:hover{
        background: linear-gradient(135deg, rgb(24, 17, 55), rgb(76, 76, 179));
    }

    .pagination-links > .flex > div:first-child > span:hover, 
    .pagination-links > .flex > div:last-child > div:last-child > span > span:hover{
        cursor: not-allowed;
    }

    .pagination-links > .flex div:last-child{
        gap: 10px;
    }
    .pagination-links > .flex > div:last-child > div:last-child span{
        width: 100%;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .pagination-links > .flex .hidden{
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    svg{
        height: 15px;
        width: 15px;
    }
</style>
@endsection

@section('javascript')
@endsection
