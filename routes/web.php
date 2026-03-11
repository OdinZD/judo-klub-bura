<?php

use App\Models\Competition;
use App\Models\GalleryAlbum;
use App\Models\Post;
use Illuminate\Support\Facades\Route;

Route::view('/', 'pages.public.home')->name('home');
Route::view('/o-nama', 'pages.public.about')->name('about');
Route::view('/galerija', 'pages.public.gallery')->name('gallery');
Route::get('/galerija/{album:slug}', function (GalleryAlbum $album) {
    abort_unless($album->is_published, 404);
    $album->loadCount('images');

    return view('pages.public.gallery-album', compact('album'));
})->name('gallery.album');
Route::view('/kontakt', 'pages.public.kontakt')->name('contact');

Route::view('/novosti', 'pages.public.novosti')->name('news');
Route::get('/novosti/{post:slug}', function (Post $post) {
    abort_unless($post->is_published, 404);

    return view('pages.public.novosti-show', compact('post'));
})->name('news.show');

Route::view('/rezultati', 'pages.public.rezultati')->name('results');
Route::get('/rezultati/{competition:slug}', function (Competition $competition) {
    abort_unless($competition->is_published, 404);
    $competition->load('results');

    return view('pages.public.rezultati-show', compact('competition'));
})->name('results.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', 'verified'])->prefix('dashboard')->group(function () {
    Route::get('galerija', \App\Livewire\Admin\GalleryAlbumIndex::class)->name('admin.gallery.index');
    Route::get('galerija/create', \App\Livewire\Admin\GalleryAlbumForm::class)->name('admin.gallery.create');
    Route::get('galerija/{album}/edit', \App\Livewire\Admin\GalleryAlbumForm::class)->name('admin.gallery.edit');

    Route::get('novosti', \App\Livewire\Admin\PostIndex::class)->name('admin.posts.index');
    Route::get('novosti/create', \App\Livewire\Admin\PostForm::class)->name('admin.posts.create');
    Route::get('novosti/{post}/edit', \App\Livewire\Admin\PostForm::class)->name('admin.posts.edit');

    Route::get('rezultati', \App\Livewire\Admin\CompetitionIndex::class)->name('admin.competitions.index');
    Route::get('rezultati/create', \App\Livewire\Admin\CompetitionForm::class)->name('admin.competitions.create');
    Route::get('rezultati/{competition}/edit', \App\Livewire\Admin\CompetitionForm::class)->name('admin.competitions.edit');

    Route::get('raspored', \App\Livewire\Admin\TrainingScheduleManager::class)->name('admin.schedule');
    Route::get('treneri', \App\Livewire\Admin\CoachIndex::class)->name('admin.coaches');
    Route::get('poruke', \App\Livewire\Admin\ContactMessageIndex::class)->name('admin.messages');
});

require __DIR__.'/settings.php';
