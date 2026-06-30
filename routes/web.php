<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::livewire('/', 'pages::ideas-index')->middleware('auth');
Route::livewire('/register', 'pages::register')->middleware('guest');
Route::livewire('/login', 'pages::login')->name('login')->middleware('guest');

Route::get('/logout', function() {
    Auth::logout();
    session()->invalidate();
    return redirect('/');

});