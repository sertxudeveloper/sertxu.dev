<?php

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

Route::get('/', Controllers\HomeController::class)->name('home');

Route::get('/education', [Controllers\EducationController::class, 'index'])->name('education.index');
Route::get('/experience', [Controllers\ExperienceController::class, 'index'])->name('experience.index');

Route::view('/projects', 'projects.index')->name('projects.index');
Route::get('/projects/{project:slug}', [Controllers\ProjectController::class, 'show'])->name('projects.show');

Route::view('/blog', 'posts.index')->name('posts.index');
Route::get('/blog/{post:slug}', [Controllers\PostController::class, 'show'])->name('posts.show');

Route::view('/uses', 'uses')->name('uses');

Route::middleware('auth')->group(function () {
    Route::get('/blog/{post:slug}/preview', Controllers\PostPreviewController::class)->name('posts.preview');
});
