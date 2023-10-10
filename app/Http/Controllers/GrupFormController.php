<?php

namespace App\Http\Controllers;

use App\Models\GrupForm;
use Illuminate\Http\Request;

class GrupFormController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:grupform-read')->only('index');
        $this->middleware('permission:grupform-create')->only(['create', 'store']);
        $this->middleware('permission:grupform-update')->only(['edit', 'update']);
        $this->middleware('permission:grupform-delete')->only('delete');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function show(GrupForm $grupForm)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GrupForm $grupForm)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GrupForm $grupForm)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GrupForm $grupForm)
    {
        //
    }
}
