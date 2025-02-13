<?php

declare(strict_types=1);

namespace App\Chat\Factories;

use App\Chat\Models\ChatHistory;
use App\Chat\Models\CustomerServiceOperator;
use App\Chat\Models\CustomerService;
use App\Chat\Models\QuestionType;

use App\MtSports\Models\Client;

class ChatHistoryFactory
{
    public function create(
        Client $client,
        QuestionType $questionType,
        CustomerService $customerService,
        CustomerServiceOperator $operator
    ): ChatHistory {
        $chatHistory = new ChatHistory();
        $chatHistory->client()->associate($client);
        $chatHistory->questionType()->associate($questionType);
        $chatHistory->chat_date = \date('Y-m-d H:i:s');
        $chatHistory->customerService()->associate($customerService);
        $chatHistory->operator()->associate($operator);

        return $chatHistory;
    }

    public function updateCustomerService(
        CustomerService $customerService,
        ChatHistory $chatHistory
    ): ChatHistory {
        $chatHistory->customerService()->associate($customerService);

        return $chatHistory;
    }

    public function updateOperator(
        CustomerServiceOperator $operator,
        ChatHistory $chatHistory
    ): ChatHistory {
        $chatHistory->operator()->associate($operator);

        return $chatHistory;
    }

    public function createForAnonymousClient(
        QuestionType $questionType,
        CustomerService $customerService,
        CustomerServiceOperator $operator
    ): ChatHistory {
        $chatHistory = new ChatHistory();
        $chatHistory->client_id = 0;
        $chatHistory->questionType()->associate($questionType);
        $chatHistory->chat_date = \date('Y-m-d H:i:s');
        $chatHistory->customerService()->associate($customerService);
        $chatHistory->operator()->associate($operator);

        return $chatHistory;
    }
}
