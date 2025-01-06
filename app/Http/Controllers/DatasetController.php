<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Pagination\LengthAwarePaginator;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $response = Http::timeout(600)->post('http://127.0.0.1:5000/dataset');
        $data = $response->json();
        $dataset = collect($data['dataset']);
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $dataset->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedDataset = new LengthAwarePaginator(
            $currentItems, 
            $dataset->count(), // Total items
            $perPage, // Items per page
            $currentPage, // Current page
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

        return view("dataset.dataset", compact("paginatedDataset"));
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
}
