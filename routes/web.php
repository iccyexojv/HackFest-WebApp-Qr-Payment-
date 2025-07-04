<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('home-page');
});

Route::get('/fest', function () {
    return view('fest-page');
});

Route::get('/about-us', function () {
    return view('about-us');
});

Route::get('/contact-us', function () {
    return view('contact-us');
});
