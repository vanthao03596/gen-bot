<?php

use App\Http\Controllers\BotManController;
use Illuminate\Support\Facades\Route;
use BotMan\BotMan\Messages\Attachments\Location;
use BotMan\BotMan\Messages\Outgoing\OutgoingMessage;
use BotMan\Drivers\Telegram\TelegramDriver;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::match(['get', 'post'], '/botman', [BotManController::class, 'handle']);

Route::match(['get', 'post'], '/botman-group', [BotManController::class, 'handleGroup']);

Route::match(['get', 'post'], '/botman-9', [BotManController::class, 'handleBot9']);

Route::match(['get', 'post'], '/botman-dragonsea', [BotManController::class, 'handleBotDragonsea']);

Route::get('/botman/tinker', [BotManController::class, 'tinker']);

Route::get('/botman-group/tinker', [BotManController::class, 'groupTinker']);

Route::get('/botman-9/tinker', [BotManController::class, 'tinker9']);

Route::get('/botman-dragonsea/tinker', [BotManController::class, 'tinkerDragonsea']);