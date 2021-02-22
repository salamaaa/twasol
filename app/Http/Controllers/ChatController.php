<?php

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function rooms(){
        return ChatRoom::all();
    }

    public function messages($roomId){
        return ChatMessage::where('chat_room_id',$roomId)
            ->with('user')
            ->orderBy('created_at','DESC')
            ->get();
    }

    public function newMessage(Request $request,$roomId){
        $message = new ChatMessage;
        $message->user_id = Auth::id();
        $message->chat_room_id = $roomId;
        $message->message = $request->message;
        $message->save();
        return $message;
    }
}
