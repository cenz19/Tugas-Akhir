<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
set_time_limit(0);
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view("post.post");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }

    public function predict(Request $request)
    {
        $text = $request->text;

        // Send the text to the Flask server
        $response = Http::post('http://127.0.0.1:5000/predict', [
            'text' => $text
        ]);

        // Decode the JSON response from Flask
        $data = $response->json();

        // Check if the response is successful and contains 'category'
        if (isset($data['label_index'])) {
            $label_names = ['non-cyberbullying', 'flaming', 'harassment', 'denigration'];
            $category = $label_names[$data['label_index']]; // Extract the category
            return response()->json([
                'status' => 'oke',
                'msg' => view('post.predict', compact('category','text'))->render(),
            ]);
        }
        // Handle failure case
        return response()->json([
            'status' => 'failed',
            'msg' => 'Prediction failed'
        ]);
    }

    public function sendPost(Request $request){
        $text = $request->text;
        $socialMedia = $request->socialMedia;
        $response = Http::timeout(600)->post('http://127.0.0.1:5000/sendPost',[
            'text' => $text,
            'socialMedia' => $socialMedia
        ]);
        $data = $response->json();
        
        if (isset($data['status']) && $data['status'] === 'success') {
            // Redirect with a success message stored in the session
            return redirect()->back()->with('success', 'Post sent successfully!');
        }
        // Redirect with an error message stored in the session
        return redirect()->back()->with('error', 'Post failed to send.');
    }
}
