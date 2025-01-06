<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;
set_time_limit(0);
class ScrapeController extends Controller
{
    public function index(Request $request)
    {
        $label = [];
        $posts = [];
        $posts_pagination = null;
        $time = "";
        //Menghitung jumlah data dari setiap label
        $getLabels = Http::post('http://127.0.0.1:5000/count_labels');
        $labels_response = $getLabels->json();
        $labels = $labels_response['labels'];
        

        // File Temp (Latest Search)
        $getTemp = Http::post('http://127.0.0.1:5000/temp');
        $temp_response = $getTemp->json();

        if (isset($temp_response['temp'])) {
            $posts = $temp_response['temp'];
            $time = $temp_response['time'];
            $perPage = 5;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $pagedData = array_slice($posts, ($currentPage - 1) * $perPage, $perPage);
            $posts_pagination = new LengthAwarePaginator($pagedData, count($posts), $perPage, $currentPage, [
                'path' => $request->url(),
                'query' => $request->query(),
            ]);
        }
        return view('scrape.scrape', compact('labels', 'posts_pagination', 'time'));
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function getscrapedata(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'keyword' => 'required|string|max:255',
            'maxData' => 'required|integer|min:1',
            'socialMedia' => 'required|in:x,threads',
        ]);

        try {
            $response = Http::timeout(600)->post('http://127.0.0.1:5000/scrape', [
                'keyword' => $validated['keyword'],
                'socialMedia' => $validated['socialMedia'],
                'maxData' => $validated['maxData'],
            ]);

            $data = $response->json();

            if ($response->successful() && $data['status'] === "oke" && count($data['posts']) > 0) {
                $html = '<div style="justify-content:center;display:flex;flex-direction:column;align-items:center;">
                            <p>Your Data Successfully Crawled</p>
                            <a href="'.route('scrape.index').'" class="btn">See The Result</a>
                        </div>';
                return response()->json([
                    'status' => 'oke',
                    'msg' => $html
                ]);
            }

            return response()->json([
                'status' => 'error',
                'msg' => 'No posting found with ' + $validated['keywords'] + ' keyword.'
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'msg' => 'An unexpected error occurred. Please try again later.'
            ], 500);
        }
    }


    public function updateLabel(Request $request)
    {
        $postId = $request->input('postId');
        $newLabel = $request->input('newLabel');

        $response = Http::post('http://127.0.0.1:5000/update_label', [
            'postId' => $postId,
            'newLabel' => $newLabel
        ]);

        if ($response->successful()) {
            return response()->json(['message' => 'Label updated successfully']);
        } else {
            return response()->json(['message' => 'Failed to update label'], 500);
        }
    }

    public function retrain()
    {
        $response = Http::timeout(600)->post('http://127.0.0.1:5000/retrain');
        $data = $response->json();
        if ($data['message'] == 'ok') {
            return view('scrape.retrain');
        } else {
            return redirect()->back()->withErrors(['error' => 'Retraining failed.']);
        }
    }
}
