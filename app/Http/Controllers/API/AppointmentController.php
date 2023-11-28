<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
// appointment
use App\Models\Appointment;
use App\Models\Doctor;
use Carbon\Carbon;
use App\Models\User;


class AppointmentController extends Controller
{
    public function index()
    {
        $appointment = Appointment::all();
        return response()->json([
            'data' => $appointment,
        ]);
    }

    public function store(Request $request)
    {
         try {
        $doctor = Doctor::find($request->doctor_id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found.'], 404);
        }

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

        $user = User::find(auth()->user()->id);
        if (!$user) {
            return response()->json(['message' => 'User not found.'], 404);
        }

        $user->phone = $request->phone;
        $user->save();

        return response()->json([
            'message' => 'Appointment created successfully.',
            'data' => $data,
        ],201);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'An error occurred while creating the appointment.',
            'error' => $e->getMessage(),
        ], 500);
    }
    }

    public function missing(Request $request, string $id)
    {
        $appointment = Appointment::find($request->id);
        // dd($appointment);
        $appointment['status'] = 'Missing';
        $appointment->save();
        return response()->json([
            'message' => 'Appointment has been set to missing successfully.',
            'data' => $appointment,
        ]);
    }
    public function done(Request $request, string $id)
    {
        $appointment = Appointment::find($request->id);
        // dd($appointment);
        $appointment['status'] = 'Done';
        $appointment->save();
        return response()->json([
            'message' => 'Appointment has been set to done successfully.',
            'data' => $appointment,
        ]);
    }
    public function dataUser(Request $request)
    {
        $appointment = Appointment::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'data' => $appointment,
        ]);
    }
}
