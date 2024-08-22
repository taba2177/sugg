<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Complaint;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/complaints/count-unread', function() {
    $count = Complaint::where('status', 'unread')->count();
    return response()->json(['count' => $count]);
});
