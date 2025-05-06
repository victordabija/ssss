<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/students/{student}', [StudentController::class, 'show'])->name('students.show');

Route::get('/assets/{path}', function (string $path) {
    return redirect()->to("https://api.ceiti.md/assets/$path");
})->where('path', '.*');
