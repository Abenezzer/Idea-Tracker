<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/register', 'pages::register')->middleware('guest');
Route::livewire('/login', 'pages::login')->name('login')->middleware('guest');
Route::livewire('/', 'pages::ideas-index')->middleware('auth');
Route::livewire('/ideas/create', 'pages::ideas-create')->middleware('auth');
Route::livewire('/ideas/{idea}', 'pages::idea-show')->middleware('auth');



Route::get('/logout', function() {
    Auth::logout();
    session()->invalidate();
    return redirect('/');
});