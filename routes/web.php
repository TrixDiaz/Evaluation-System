<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuizController;

Route::get('/', function () {
    return view('welcome');
});

// Quiz routes
Route::group(['prefix' => 'quizzes'], function () {
    // View quiz
    Route::get('{quiz}', [QuizController::class, 'show'])->name('quiz.show');

    // Submit quiz
    Route::post('{quiz}/submit', [QuizController::class, 'submit'])->name('quiz.submit');
});

// Quiz results (not under 'quizzes' prefix)
Route::get('/quiz-results/{attempt}', [QuizController::class, 'results'])->name('quiz.results');
