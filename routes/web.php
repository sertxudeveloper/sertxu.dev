<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects2', [ProjectController::class, 'index2'])->name('projects.index2');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
