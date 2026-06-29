<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;

Route::put('/orders/{id}/status', [OrderController::class, 'updateStatus']);
Route::get('/orders/{id}/history', [OrderController::class, 'history']);
