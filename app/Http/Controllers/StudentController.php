<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function show(Student $student)
    {
        return view('student.show', [
            'student' => $student,
        ]);

    }
}
