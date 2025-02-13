<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\FeedbackRepository;

class FeedbackServices
{
    public function __construct(
        private FeedbackRepository $feedbackRepository,
        private StaticStorageService $staticStorageService,
    ) {}

    public function list(int $page = 1, int $size = 20)
    {
        $result = $this->feedbackRepository->listFeedback($page, $size);
        $result->total = $result->data->totalItems;

        return $result;
    }

    public function update(array $feedback)
    {
        return $this->feedbackRepository->update($feedback);
    }

    public function destroy(int $id)
    {
        return $this->feedbackRepository->destroy($id);
    }

}