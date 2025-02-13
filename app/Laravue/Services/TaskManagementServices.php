<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\TaskManagementRepository;
use Illuminate\Http\UploadedFile;

class TaskManagementServices
{
    public function __construct(
        private TaskManagementRepository $taskManagementRepository,
        private StaticStorageService $staticStorageService,
    ) {}

    public function listTestManagement(int $page = 1, int $size = 20)
    {
        $result = $this->taskManagementRepository->listTestManagement($page, $size);
        $result->total = $result->data->totalItems;

        return $result;
    }

    public function store(array $task)
    {
        return $this->taskManagementRepository->store($task);
    }

    public function update(array $task)
    {
        return $this->taskManagementRepository->update($task);
    }

    public function destroy(int $id)
    {
        return $this->taskManagementRepository->destroy($id);
    }

    public function imageUpload(UploadedFile $file): string
    {
        $types = ['gif', 'jpeg', 'jpg', 'png', 'svg'];

        if (!in_array($file->clientExtension(), $types)) {
            throw new \LogicException('Invalid field type');
        }

        return $this->staticStorageService->uploadImage($file);
    }
    
    public function dailyList()
    {
        return $this->taskManagementRepository->dailyList();
    }

    public function companyList()
    {
        return $this->taskManagementRepository->companyList();
    }

    public function itemList()
    {
        return $this->taskManagementRepository->itemList();
    }

    public function getRecord(int $page = 1, int $size = 20, string $from = '', string $to = '')
    {
        $result = $this->taskManagementRepository->getRecord($page, $size, $from, $to);
        $result->total = $result->data->totalItems;

        return $result;
    }

    public function getDetails(int $page = 1, int $size = 20, string $from = '', string $to = '', int $uid = 0)
    {
        $result = $this->taskManagementRepository->getDetails($page, $size, $from, $to, $uid);
        $result->total = $result->data->totalItems;

        return $result;
    }

}