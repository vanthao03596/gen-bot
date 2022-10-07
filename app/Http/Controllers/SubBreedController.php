<?php

namespace App\Http\Controllers;

use App\Services\DogService;
use Illuminate\Http\Request;

class SubBreedController extends Controller
{
    public function __construct()
    {
        $this->photos = new DogService;
    }

    public function random($bot, $breed, $subBreed)
    {
        $bot->reply($this->photos->bySubBreed($breed, $subBreed));
    }
}
