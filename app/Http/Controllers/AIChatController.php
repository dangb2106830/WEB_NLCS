<?php

namespace App\Http\Controllers;

use App\Services\AI\ChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AIChatController extends Controller
{
    public function __invoke(Request $request, ChatService $chatService): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:1000'],
        ]);

        try {
            $result = $chatService->answer($validated['message']);

            return response()->json([
                'answer' => $result['answer'],
                'sources' => $result['sources'],
            ]);
        } catch (Throwable $exception) {
            report($exception);

            return response()->json([
                'message' => 'Chatbot đang tạm thời bận. Vui lòng thử lại sau ít phút.',
            ], 500);
        }
    }
}
