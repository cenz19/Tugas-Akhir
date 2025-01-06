@extends('layouts.app')

@section('title')
<a class="navbar-brand" href="#">Dashboard</a>
@endsection
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        Overview
                    </div>
                    <div class="card-body">
                        <input type="text" id="text-input" placeholder="Enter text for prediction">
                        <a type="button" onclick="predict()" href="#modal">Predict</a>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        Detailed Report
                    </div>
                    <div class="card-body">
                        <p>This is where more detailed information or graphs can go.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('javascript')
<script>
    function predict() {
        const text = $('#text-input').val();  // Get the input value
        console.log('Text:', text);

        $.ajax({
            type: 'POST',
            url: '{{ route("dashboard.predict") }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'text': text,
            },
            success: function(data) {
                console.log(data);  // Check AJAX response data
                if (data.status === 'oke') {
                    $('#prediction-result').html(data.msg);
                } else {
                    alert('Failed to load prediction');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    }
</script>

@endsection
