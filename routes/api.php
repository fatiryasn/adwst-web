<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TicketController;

Route::middleware('api.key')->group(function () {
    //get ticket by code
    Route::get('/ticket/code/{code}', [TicketController::class, 'showByCode']);

    //check-in a ticket
    Route::post('/ticket/{id}/check-in', [TicketController::class, 'checkIn']);

    //test api key validity
    Route::get('/ping', [TicketController::class, 'ping']);
});
