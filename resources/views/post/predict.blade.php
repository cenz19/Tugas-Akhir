<div id="predicted-category" style="margin:10px 20px;">
    <p>Your Text contains {{ $category }}</p>
    <br>
    
    <!-- Form for posting to X -->
    <form method="POST" action="{{ route('post.sendPost') }}" style="display: inline;">
        @csrf
        <input type="hidden" name="socialMedia" value="x">
        <input type="hidden" name="text" value="{{ $text }}">
        <button type="submit" class="btn">Post to X</button>
    </form>

    <!-- Form for posting to Threads -->
    <form method="POST" action="{{ route('post.sendPost') }}" style="display: inline;">
        @csrf
        <input type="hidden" name="socialMedia" value="threads">
        <input type="hidden" name="text" value="{{ $text }}">
        <button type="submit" class="btn">Post to Threads</button>
    </form>
</div>

<style>
    .btn {
        width: 20%; 
        padding: 10px; 
        background: linear-gradient(135deg, rgb(11, 29, 64), rgb(64, 108, 179)); 
        color: white; 
        border: none; 
        border-radius: 4px; 
        cursor: pointer;
    }
</style>
