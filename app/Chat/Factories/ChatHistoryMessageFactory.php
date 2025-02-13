<?php

declare(strict_types=1);

namespace App\Chat\Factories;

use App\Chat\Models\ChatHistory;
use App\Chat\Models\ChatHistoryMessage;

class ChatHistoryMessageFactory
{
    public function createForChatHistory(
        string $from,
        ChatHistory $chatHistory,
        string $content,
        ?string $mediaUrl = null
    ): ChatHistoryMessage {
        $chatHistoryMessage = new ChatHistoryMessage();
        $chatHistoryMessage->from = $from;
        $chatHistoryMessage->message_content = $content;
        $chatHistoryMessage->media_uri = $mediaUrl;
        $chatHistoryMessage->chatHistory()->associate($chatHistory);
        $chatHistoryMessage->save();

        return $chatHistoryMessage;
    }
}
