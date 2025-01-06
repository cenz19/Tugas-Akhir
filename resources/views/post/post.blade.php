@extends('layouts.app')

@section('title')
<p style="font-weight: 400; color:rgba(255,255,255,0.5);">Pages <span style="color:#fff">/ Post</span></p>
<p>Post</p>
@endsection

@section('content')
    <div class="container">
        <div class="card" style="border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <div class="card-header" style="padding: 15px; font-size: 1.5em; border-bottom: 1px solid #ccc; border-radius: 8px 8px 0 0;">
                <div class="flex" style="justify-content: space-between;">
                    <p>Posting</p>
                    <i class="bi bi-mailbox"></i>
                </div>
            </div>
            <div class="card-body" style="padding: 20px;">
                <input type="text" id="text-input" placeholder="Enter text for prediction" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 10px;" required>
                <button type="button" onclick="predict()" style="width: 100%; padding: 10px; background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179)); color: white; border: none; border-radius: 4px; cursor: pointer;">
                    Predict
                </button>
            </div>
        </div>
        <div class="card bg" style="position:relative; border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);">
            <div class="card-body" style="position: absolute; bottom: 0;padding: 20px; color: white;">
                <i class="bi bi-chat-left-quote"></i>
                <h3 style="margin-bottom: 10px;">Quote of the day</h3>
                <blockquote style="font-style: italic; border-left: 4px solid #007bff; padding-left: 10px;">
                    "Treat others online as you would like to be treated. Cyberbullying is never okay!"
                </blockquote>
            </div>
        </div>
        <div class="card" style="border-radius: 8px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);margin:0;">
            <div id="prediction-result">
                <div id="loading-spinner" style="display: none;">
                    <!-- You can style this spinner as desired -->
                    <div class="spinner"></div>
                </div>
            </div>
        </div>
       
    </div>
    <style>
        .container {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        .bg{
            /* background-image: url('{{asset('img/bg.jpg')}}'); */
            background-image: url('https://images.unsplash.com/photo-1604856420566-576ba98b53cd?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
            background-repeat: no-repeat;
            background-size: 100% 100%;
        }
        /* Spinner Styles */
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #ccc;
            border-top: 4px solid #007bff; /* Use your primary color */
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: auto;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

    </style>
@endsection

@section('javascript')
<script>
    function predict() {
        const text = document.getElementById('text-input').value;
        if (!text.trim()) {
            alert('Please enter some text before making a prediction.');
            textInput.value = '';
            return;
        }
        console.log('Text:', text);
        $('#loading-spinner').show();
        $('#prediction-result').html('');
        $.ajax({
            type: 'POST',
            url: '{{ route("post.predict") }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'text': text,
            },
            success: function(data) {
                console.log(data);
                $('#loading-spinner').hide();

                if (data.status === 'oke') {
                    $('#prediction-result').html(data.msg);
                } else {
                    $('#prediction-result').html('<p>Failed to load prediction</p>');
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                $('#loading-spinner').hide();
                $('#prediction-result').html('<p>An error occurred. Please try again.</p>');
            }
        });
    }
</script>
@endsection


