<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Appointment;
// use doctor
use App\Models\Doctor;
// use carbon
use Carbon\Carbon;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $appointments = Appointment::all();
        // appointment sort by date
        $appointments = Appointment::orderBy('appointment_date', 'desc')->get();
        return view('pages.backend.appointment.index',compact('appointments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::all();
        return view('pages.backend.appointment.create',compact('doctors'));
    }
    
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        dd($request->appointment_date);
        // dd($request->doctor_id)
        $doctor = Doctor::find($request->doctor_id);
        $startTime = Carbon::createFromFormat('H:i:s', $doctor->start_time);
        $CheckAppointmentQueue = Appointment::where('appointment_date', $request->appointment_date)->where('doctor_id', $request->doctor_id)->get();
        $appointmentTime = $startTime->addMinutes(30*$CheckAppointmentQueue->count())->format('H:i:s');
        $inisial = implode('', array_map(fn($word) => strtoupper(substr($word, 0, 1)), explode(' ', $doctor->name)));
        $data = $request->all();
        $data['user_id'] = auth()->user()->id;
        $data['appointment_code'] = $inisial . '-' . $request->appointment_date .($CheckAppointmentQueue->count()+1);
        $data['status'] = 'Waiting';
        $data['no_queue'] = $CheckAppointmentQueue->count() + 1;
        $data['appointment_time'] = $appointmentTime;
        Appointment::create($data);
        alert()->success('Success','Appointment created successfully.');
        return redirect()->route('appointment.index');
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
    public function missing(Request $request, string $id)
    {
        $appointment = Appointment::find($request->id);
        // dd($appointment);
        $appointment['status'] = 'Missing';
        $appointment->save();
        alert()->success('Success','Appointment missing successfully.');
        return redirect()->route('appointment.index');
    }
    public function done(Request $request, string $id)
    {
        $appointment = Appointment::find($request->id);
        // dd($appointment);
        $appointment['status'] = 'Done';
        $appointment->save();
        alert()->success('Success','Appointment done successfully.');
        return redirect()->route('appointment.index');
    }
}
