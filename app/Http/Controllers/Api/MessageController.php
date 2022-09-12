<?php

namespace App\Http\Controllers\Api;

use App\Events\Chat\SendMessage;
use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpFoundation\Response;

class MessageController extends Controller
{
    public function index($userId)
    {

        $userFrom = Auth::id();
        $userTo = $userId;

        $messages = Message::where(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userFrom,
                    'to' => $userTo
                ]);
            }
        )->orWhere(
            function ($query) use ($userFrom, $userTo) {
                $query->where([
                    'from' => $userTo,
                    'to' => $userFrom
                ]);
            }
        )->orderBy('created_at', 'ASC')->get();

        return response()->json([
            'messages' => $messages
        ], Response::HTTP_OK);
    }

    public function store(Request $request) {
        $messages = new Message();

        $messages->from = Auth::id();
        $messages->to = $request->to;
        $messages->content = filter_var($request->content, FILTER_SANITIZE_STRIPPED);

        try {
            $messages->save();

            Event::dispatch(new SendMessage($messages, $request->to));

        } catch (\Throwable $th) {
            return response()->json([
                'erro' => "406"
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
