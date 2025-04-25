<?php

use App\Http\Controllers\Admin\TestController;
use Illuminate\Support\Facades\Route;
Route::get('/1', [TestController::class, 'response']);
