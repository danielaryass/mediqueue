<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class DoctorController extends Controller
{
     public function index()
     {
        // abort_if(Gate::denies('Akses Admin'), Response::HTTP_FORBIDDEN, '403 Forbidden');
            $doctor = Doctor::all();
            return response()->json([
                'data' => $doctor,
            ]);
     }
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
    return response()->json([
        'message' => 'Doctor created successfully.',
        'data' => $doctor,
    ]);
    }
    public function show(string $id)
    {
        $doctor  = Doctor::findOrFail($id);
        return response()->json([
            'data' => $doctor,
            'status' => 'success'
        ],200);
    }
}
