<?php

use Illuminate\Support\Facades\Route;
use SeavSeyla\Announcements\Models\Announcement;
Route::get('/', function () {
    $ann = Announcement::first();
    // dd($ann->user->name);
    return view('announcements::list');
    
});
