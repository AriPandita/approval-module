<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovalController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/approval/create', [ApprovalController::class, 'create']);
Route::post('/approval/process', [ApprovalController::class, 'process']);
Route::post('/approval/detail', [ApprovalController::class, 'detail']);
Route::post('approval/get-by-approver', [ApprovalController::class, 'approver']);
Route::post('approval/get-by-requester', [ApprovalController::class, 'requester']);