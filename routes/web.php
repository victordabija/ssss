<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/students/{student:idnp}', [StudentController::class, 'show'])->name('students.show');

// todo: fix
Route::get('/assets/{path}', function (string $path) {
    return redirect()->to("https://api.ceiti.md/assets/$path");
})->where('path', '.*');
