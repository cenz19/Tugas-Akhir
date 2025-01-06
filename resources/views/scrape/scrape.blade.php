@extends('layouts.app')

@section('title')
<p style="font-weight: 400; color:rgba(255,255,255,0.5);">Pages <span style="color:#fff">/ Scrape</span></p>
<p>Scrape</p>
@endsection

@section('content')
<div class="container">
    <div class="card" style="border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); height:270px;">
        <div class="card-header" style="padding: 15px; font-size: 1.5em; border-bottom: 1px solid #ccc; border-radius: 8px 8px 0 0;">
            <div class="flex" style="justify-content: space-between;">
                <p>Scrape</p>
                <i class="bi bi-binoculars"></i>
            </div>
        </div>
        <div class="card-body" style="padding: 20px;">
            <form onsubmit="scrape(event)">
                <div class="radio-button-group">
                    <input type="radio" id="x" name="socialmedia" value="x" checked>
                    <label for="x">
                        <img src="{{ asset('svg/twitter-x.svg') }}" alt="X Logo" class="icon" >
                        <span class="label-name">X</span>
                    </label>
    
                    <input type="radio" id="threads" name="socialmedia" value="threads">
                    <label for="threads">
                        <img src="{{ asset('svg/threads.svg') }}" alt="Threads Logo" class="icon">
                        <span class="label-name">Threads</span>
                    </label>
                </div>
    
                <input type="text" id="text-input" placeholder="Enter the keyword" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;" required>
                <input type="number" id="number-input" placeholder="Enter the max scraped data" value="1" min="1" step="1" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;" required>
                <button type="submit" style="width: 100%; padding: 10px; background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179)); color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Scrape
                </button>
            </form>
        </div>
    </div>
    <div class="card-body card scraped-data" style="position:relative;">
        <div class="card-header" style="padding: 15px; font-size: 1.5em; border-bottom: 1px solid #ccc; border-radius: 8px 8px 0 0;">
            <div class="flex" style="justify-content: space-between;">
                <p>Latest crawled data</p>
                <i class="bi bi-clipboard-data"></i>
            </div>
        </div>
        <div id='scrape-result' style="padding:20px;">
            @if($posts_pagination)
                @include('scrape.scrapetable', ['posts_pagination' => $posts_pagination, 'time' => $time])
            @else
                <p style="color: red; font-weight:600;">You have not search any data. Please initiate a scrape to view results.</p>
            @endif
        </div>
        
        <div id="loading-spinner" style="display:none;">
            <div class="spinner"></div>
            <p>Loading...</p>
        </div>
    </div>
    <div class="card" style="border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);height:380px;">
        <div class="card-header" style="padding: 15px; font-size: 1.5em; border-bottom: 1px solid #ccc; border-radius: 8px 8px 0 0;">
            <div class="flex" style="justify-content: space-between;">
                <p style="font-size: 21px;">Total Distribution by Category</p>
                <i class="bi bi-bar-chart-line"></i>
            </div>
        </div>
        <div class="card-body" style="padding: 10px;">
            <div id="chartContainer"></div>
        </div>
    </div>
    <div class="card" style="border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);height:440px;">
        <div class="card-header" style="padding: 15px; font-size: 1.5em; border-bottom: 1px solid #ccc; border-radius: 8px 8px 0 0;">
            <div class="flex" style="justify-content: space-between;">
                <p style="font-size: 21px;">Percentage Distribution by Category</p>
                <i class="bi bi-pie-chart"></i>
            </div>
        </div>
        <div class="card-body" style="padding: 10px;">
            <div id="piechart"></div>
        </div>
    </div>
</div>

<style>
    .container {
        display: grid;
        grid-template-columns: 1fr 3.5fr;
        grid-template-rows: auto auto auto;   
        gap: 20px;
    }
    .scraped-data{
        grid-column: 2 / 3;
        grid-row: 1 / 4;
        /* max-height: 800px;
        overflow-y: auto; */
    }
    .radio-button-group{
        margin-bottom: 10px;
        display: flex;
        justify-content: center;
        gap: 10px;
    }

    /* Hide the default radio buttons */
    .radio-button-group input[type="radio"] {
        display: none;
    }

    /* Style labels as buttons */
    .radio-button-group label {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 5px 8px;
        border-radius: 8px;
        background-color: #f3f4f6;
        border: 1px solid #ccc;
        cursor: pointer;
        margin: 0 1px;
        transition: all 0.3s;
        font-weight: 500;
        color: #1e293b;
        gap: 2px;
    }

    /* Icon styling */
    .radio-button-group .icon {
        width: 18px;
        height: 18px;
        /* margin-right: 4px; */
        transition: filter 0.3s;
    }
    .label-name{
        font-size: 14px;
    }
    /* Checked state: change background, text, and icon color */
    .radio-button-group input[type="radio"]:checked + label {
        background-color: black;
        color: #ffffff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        border-color: #3b82f6;
    }

    /* Invert the color of the icon to make it white when selected */
    .radio-button-group input[type="radio"]:checked + label .icon {
        filter: brightness(0) invert(1);
    }

    /* Hover effect */
    .radio-button-group label:hover {
        background-color: #dbeafe;
    }
    /* Spinner CSS */
    /* Spinner CSS */
    .spinner {
        border: 8px solid #f3f3f3; /* Light gray background */
        border-top: 8px solid #3498db; /* Blue color */
        border-radius: 50%;
        width: 50px;
        height: 50px;
        animation: spin 2s linear infinite;
    }

    /* Animation for spinning */
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    /* Center the spinner absolutely within the parent container */
    #loading-spinner {
        position: absolute;
        display: flex;
        flex-direction: column;
        align-items: center;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
    }

    /* Center the text below the spinner */
    #loading-spinner p {
        margin-top: 10px;
        color: #3498db;
        font-weight: bold;
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

@endsection
@section('javascript')
<script>
    function scrape(event) {
        event.preventDefault();

        // Clear previous results and show loading spinner
        const resultContainer = document.getElementById('scrape-result');
        const loadingSpinner = document.getElementById('loading-spinner');
        resultContainer.innerHTML = '';
        loadingSpinner.style.display = 'block';

        const socialMedia = document.querySelector('input[name="socialmedia"]:checked').value;
        const keyword = document.getElementById('text-input').value;
        const maxData = parseInt(document.getElementById('number-input').value);

        // Make AJAX request
        $.ajax({
            type: 'POST',
            url: '{{ route("scrape.getscrapedata") }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'socialMedia': socialMedia,
                'keyword': keyword,
                'maxData': maxData
            },
            timeout: 600000,
            success: function(data) {
                console.log('Response:', data);
                if (data.status === 'oke') {
                    resultContainer.innerHTML = data.msg;
                } else if (data.status === 'error'){
                    resultContainer.innerHTML = `<p style="color: red;">${data.msg} ${data.status}</p>`;
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', status, error);
                resultContainer.innerHTML = `<p style="color: red;">An unexpected error occurred. Please try again later.</p>`;
            },
            complete: function() {
                loadingSpinner.style.display = 'none';
            }
        });
    }
</script>
<script type="text/javascript">
    window.onload = function () {
        // Pie Chart
        var total = {{$labels[0] + $labels[1] + $labels[2] + $labels[3]}};
        var pieOptions = {
            exportEnabled: true,
            animationEnabled: true,
            legend: {
                enabled: false // Disable the legend
            },
            data: [{
                type: "pie",
                showInLegend: true,
                indexLabel: "{y}%",
                toolTipContent: "<b>{name}</b>: {y}%",
                indexLabelPlacement: "inside",
                dataPoints: [
                    { y: ({{$labels[0]}} * 100 / total).toFixed(2), name: "Non-cyberbullying" },
                    { y: ({{$labels[1]}} * 100 / total).toFixed(2), name: "Flaming" },
                    { y: ({{$labels[2]}} * 100 / total).toFixed(2), name: "Harassment" },
                    { y: ({{$labels[3]}} * 100 / total).toFixed(2), name: "Denigration" }
                ]
            }],
            height: 300
        };
        $("#piechart").CanvasJSChart(pieOptions);

        // Column Chart
        var columnOptions = {
            animationEnabled: true,
            theme: "light1", // "light1", "light2", "dark1", "dark2"
            axisY: {
                title: "Sum of the Dataset",
                minimum: 0
            },
            axisX: {
                labelFontSize: 9  // Set font size for the labels on the X-axis
            },
            data: [{        
                type: "column",  
                dataPoints: [      
                    { y: {{$labels[0]}}, label: "Non-cyberbullying", indexLabel: "{{$labels[0]}}" },
                    { y: {{$labels[1]}}, label: "Flaming", indexLabel: "{{$labels[1]}}" },
                    { y: {{$labels[2]}}, label: "Harassment", indexLabel: "{{$labels[2]}}" },
                    { y: {{$labels[3]}}, label: "Denigration", indexLabel: "{{$labels[3]}}" },
                ]
            }],
            height: 280
        };
        var chart = new CanvasJS.Chart("chartContainer", columnOptions);
        chart.render();
    }
</script>
@endsection
