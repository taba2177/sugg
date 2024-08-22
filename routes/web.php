<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ComplaintController;

// Route::get('/complaints', function () {
//     return view('complaints_suggestions');
// });

Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');

Route::get('/', function () {
    return view('form');
});


