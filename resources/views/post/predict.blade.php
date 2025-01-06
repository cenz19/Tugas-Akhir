<div id="predicted-category" style="margin:10px 20px;">
    <p>Your Text contain {{ $category }}</p>
    <br>
    <button class="btn" onclick="postToSocialMedia('x', '{{ $text }}')">Post to X</button>
    <button class="btn" onclick="postToSocialMedia('threads', '{{ $text }}')">Post to Threads</button>
</div>
<style>
    .btn{
        width: 20%; 
        padding: 10px; 
        background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179)); 
        color: white; 
        border: none; 
        border-radius: 4px; 
        cursor: pointer;
    }
</style>
<script>
    function postToSocialMedia(socialMedia, text) {
        $.ajax({
            type: 'POST',
            url: '{{ route("post.sendPost")}}',
            data: {
                '_token': '{{ csrf_token() }}',
                'socialMedia': socialMedia,
                'text': text,
            },
            timeout: 600000,
            success: function(data){
                const predictedCategory = document.getElementById('predicted-category');
                predictedCategory.innerHTML = '';
            },
            error: function (xhr, status, error) {
                alert(`An error occurred: ${xhr.responseText || error}`);
            }
        })
    }
    
</script>