<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\ComplaintController;

// Route::get('/complaints', function () {
//     return view('complaints_suggestions');
// });

// Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::post('complaints', 'App\Http\Controllers\ComplaintController@store');

Route::get('complaints/table', [ComplaintController::class, 'index'])->name('complaints.index');

Route::get('complaints/get', [ComplaintController::class, 'getComplaints'])->name('complaints.get');

Route::post('complaints/mark-as-read/{id}', [ComplaintController::class, 'markAsRead'])->name('complaints.markAsRead');

Route::get('/', function () { return view('form');});


