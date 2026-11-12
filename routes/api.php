<?php
use Illuminate\Support\Facades\Route;

Route::get('/ping', function () {
return ['mensagem' => 'API de inscrições no ar!', 'hora' => now()->format('H:i:s')];
});