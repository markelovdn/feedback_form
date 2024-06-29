<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Feedback\FeedbackController,
    ServiceController
};
use App\Models\Service;

Route::name('api.')->group(function () {
    Route::resource('feedbacks', FeedbackController::class)->only(['store', 'show']);
    Route::apiResource('services', ServiceController::class)->only(['index']);
});
