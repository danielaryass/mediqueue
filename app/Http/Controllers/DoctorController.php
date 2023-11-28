<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;


class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $doctors = Doctor::all();
        return view('pages.backend.doctor.index',compact('doctors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::role('doctor')->get();
        return view('pages.backend.doctor.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi input
    $request->validate([
        'name' => 'required',
        'user_id' => 'required',
        'start_time' => 'required',
        'end_time' => 'required',
    ]);

    // Membuat objek Doctor
    $doctor = new Doctor;
    $doctor->name = $request->name;
    $doctor->user_id = $request->user_id;
    $doctor->start_time = $request->start_time;
    $doctor->end_time = $request->end_time;

    // Membuat direktori jika tidak ada
    $path = public_path('app/public/assets/photo-doctor');
    if (!File::isDirectory($path)) {
        $response = Storage::makeDirectory('public/assets/photo-doctor');
    }

    // Mengelola unggahan file
    if ($request->hasFile('image_url')) {
        $imagePath = $request->file('image_url')->store('assets/file-doctor', 'public');
        $doctor->image_url = $imagePath;
    } else {
        $doctor->image_url = '';
    }

    // Menyimpan data Doctor
    $doctor->save();
    return redirect()->route('doctor.index')
        ->with('success', 'Doctor created successfully.');

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
