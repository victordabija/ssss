<?php

namespace App\Http\Controllers;

use App\Models\Student;

class StudentController extends Controller
{
    public function show(Student $student)
    {
        return view('student.show', [
            'student' => $student,
        ]);

    }
}
