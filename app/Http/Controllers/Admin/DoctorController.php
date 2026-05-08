<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = \App\Models\Doctor::with('user')->get();
        return view('admin.doctors.index', compact('doctors'));
    }
}
