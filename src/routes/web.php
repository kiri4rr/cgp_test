<?php

use App\Http\Controllers\UserImageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserImageController::class, 'index']);
