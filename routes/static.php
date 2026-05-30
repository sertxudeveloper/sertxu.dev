<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Static Routes
|--------------------------------------------------------------------------
|
| Here you may register the cacheable routes for your application.
| These routes are loaded within the "static" middleware group
| which adds the appropriate headers to cache the response.
|
*/

Route::get('/', Controllers\HomeController::class)->name('home');
Route::post('/contact', [Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/projects', [Controllers\ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [Controllers\ProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [Controllers\PostController::class, 'index'])->name('posts.index');
Route::get('/blog/{post:slug}', [Controllers\PostController::class, 'show'])->name('posts.show');
Route::get('/blog/{post:slug}/thumbnail', Controllers\PostThumbnailController::class)->name('posts.thumbnail');

Route::get('/sitemap.xml', fn () => response(Storage::disk('r2')->get('sitemap.xml'), 200, ['Content-Type' => 'application/xml']));
