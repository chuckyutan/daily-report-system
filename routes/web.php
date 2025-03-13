<?php

use App\Livewire\FinalReportByBranch;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('admin/final-report-by-branch', FinalReportByBranch::class);
