<?php

declare(strict_types=1);

use App\Http\Controllers;
use Illuminate\Support\Facades\Route;

// use Illuminate\Support\Facades\Storage;

/**
 * Redirect routes
 * TODO: Temporary fix until added redirections table with Filament resource.
 */
Route::redirect('/blog/setting-up-a-kubernetes-cluster-with-microk8s', '/blog/setting-up-kubernetes-with-microk8s', 301);

// Route::get('/', Controllers\HomeController::class)->name('home');
// Route::post('/contact', [Controllers\ContactController::class, 'store'])->name('contact.store');

// Route::get('/education', [Controllers\EducationController::class, 'index'])->name('education.index');
// Route::get('/experience', [Controllers\ExperienceController::class, 'index'])->name('experience.index');

// Route::get('/projects', [Controllers\ProjectController::class, 'index'])->name('projects.index');
// Route::get('/projects/{project:slug}', [Controllers\ProjectController::class, 'show'])->name('projects.show');

// Route::get('/blog', [Controllers\PostController::class, 'index'])->name('posts.index');
// Route::get('/blog/{post:slug}', [Controllers\PostController::class, 'show'])->name('posts.show');
// Route::get('/blog/{post:slug}/thumbnail', Controllers\PostThumbnailController::class)->name('posts.thumbnail');
Route::get('/blog/{post:slug}/preview', Controllers\PostPreviewController::class)->name('posts.preview');

// Route::view('/uses', 'uses')->name('uses');

Route::middleware('auth')->group(function () {
    Route::get('/threads/auth', [Controllers\ThreadsAuthController::class, 'index'])->name('threads.auth');
    Route::get('/threads/callback', [Controllers\ThreadsAuthController::class, 'store'])->name('threads.auth-callback');
});

// Route::get('/sitemap.xml', fn () => response(Storage::disk('r2')->get('sitemap.xml'), 200, ['Content-Type' => 'application/xml']));

Route::redirect('/login', '/admin/login')->name('login');
