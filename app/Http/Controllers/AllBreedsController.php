<?php

namespace App\Http\Controllers;

use App\Services\DogService;
use Illuminate\Http\Request;

class AllBreedsController extends Controller
{
    public function __construct()
    {
        $this->photos = new DogService;
    }

    public function random($bot)
    {
        // $this->photos->random() is basically the photo URL returned from the service.
        // $bot->reply is what we will use to send a message back to the user.
        $bot->reply($this->photos->random());
    }


    public function byBreed($bot, $name)
    {
        // Because we used a wildcard in the command definition, Botman will pass it to our method.
        // Again, we let the service class handle the API call and we reply with the result we get back.
        $bot->reply($this->photos->byBreed($name));
    }
}
