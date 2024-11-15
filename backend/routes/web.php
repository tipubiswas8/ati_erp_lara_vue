<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/oracle-version', function () {
    $version = DB::connection('oracle')->select("SELECT * FROM v\$version");
    return response()->json($version);
});

