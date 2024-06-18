<?php

use App\Http\Controllers\API\SubmissionController;
use Illuminate\Support\Facades\Route;

Route::post('/submit', [SubmissionController::class, 'store']);
