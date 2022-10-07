<?php

namespace App\Http\Controllers;

use App\Conversations\DefaultConversation;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function index($bot)
    {
        // We use the startConversation method provided by botman to start a new conversation and pass
        // our conversation class as a param to it.
        $bot->startConversation(new DefaultConversation);
    }
}
