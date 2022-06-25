<?php

use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Route;

Route::post('test-code-php',[AppController::class, 'local']);
Route::post('test-code-js',[AppController::class, 'localJs']);