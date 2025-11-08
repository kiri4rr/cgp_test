<?php

use App\Http\Controllers\UserImageController;
use Illuminate\Support\Facades\Route;



Route::get('/users', [UserImageController::class, 'getUsersData']);
Route::post('/create-user', [UserImageController::class, 'create']);