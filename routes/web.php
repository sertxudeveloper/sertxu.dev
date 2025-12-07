<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', Controllers\HomeController::class)->name('home');

Route::get('/education', [Controllers\EducationController::class, 'index'])->name('education.index');
Route::get('/experience', [Controllers\ExperienceController::class, 'index'])->name('experience.index');

Route::view('/projects', 'projects.index')->name('projects.index');
Route::get('/projects/{project:slug}', [Controllers\ProjectController::class, 'show'])->name('projects.show');

Route::view('/blog', 'posts.index')->name('posts.index');
Route::get('/blog/{post:slug}', [Controllers\PostController::class, 'show'])->name('posts.show');
Route::get('/blog/{post:slug}/thumbnail', Controllers\PostThumbnailController::class)->name('posts.thumbnail');
Route::get('/blog/{post:slug}/preview', Controllers\PostPreviewController::class)->name('posts.preview');

Route::view('/uses', 'uses')->name('uses');


Route::middleware('auth')->group(function () {
    Route::get('/threads/auth', [Controllers\ThreadsAuthController::class, 'index'])->name('threads.auth');
    Route::get('/threads/callback', [Controllers\ThreadsAuthController::class, 'store'])->name('threads.auth-callback');
});

Route::get('/sitemap.xml', fn () => response(Storage::disk('r2')->get('sitemap.xml'), 200, ['Content-Type' => 'application/xml']));

Route::redirect('/login', '/admin/login')->name('login');

/**
 * Error pages
 */
Route::view('/401', 'errors.401')->name('401');
Route::view('/402', 'errors.402')->name('402');
Route::view('/403', 'errors.403')->name('403');
Route::view('/404', 'errors.404')->name('404');
Route::view('/418', 'errors.418')->name('418');
Route::view('/419', 'errors.419')->name('419');
Route::view('/429', 'errors.429')->name('429');
Route::view('/500', 'errors.500')->name('500');
Route::view('/503', 'errors.503')->name('503');
