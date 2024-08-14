<?php

use App\Http\Controllers\EducationController;
use App\Http\Controllers\ExperienceController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects2', [ProjectController::class, 'index2'])->name('projects.index2');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::view('/blog', 'blog.index')->name('blog.index');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');

Route::get('/education', [EducationController::class, 'index'])->name('education.index');
Route::get('/experience', [ExperienceController::class, 'index'])->name('experience.index');
