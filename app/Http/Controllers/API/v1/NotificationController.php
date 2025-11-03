<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\WebpushConfig;
use Kreait\Firebase\Messaging\WebpushFcmOptions;

class NotificationController extends Controller
{
    public function send(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
            'title' => 'required|string',
            'body' => 'required|string',
            'data' => 'nullable|array',
        ]);

        $messaging = app('firebase.messaging');

        $message = CloudMessage::fromArray([
            'token' => $request->token,
            'notification' => [
                'title' => $request->title,
                'body' => $request->body,
            ],
            'data' => $request->data ?? [],
        ]);

        try {
            $response = $messaging->send($message);
            return response()->json(['message' => 'Notification sent successfully.', 'response' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint' => 'required|string',
            'keys' => 'required|array',
            'keys.p256dh' => 'required|string',
            'keys.auth' => 'required|string',
        ]);

        // Save subscription in database
        $user = $request->user();

        $user->update([
            'web_push_subscription' => json_encode([
                'endpoint' => $request->endpoint,
                'keys' => $request->keys,
            ]),
        ]);

        return response()->json(['message' => 'Subscribed to notifications.']);
    }
}