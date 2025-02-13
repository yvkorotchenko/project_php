<?php

declare(strict_types=1);

namespace App\Laravue\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Laravue\Http\Requests\BannerImageUploadRequest;
use App\Laravue\Services\ResponseFactoryService;
use App\Laravue\Services\TaskManagementServices;

class TaskManagementController extends Controller
{

    public function __construct(
        private TaskManagementServices $taskManagementServices,
        private ResponseFactoryService $responseFactory,
    ) {}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 15);

        try {
            $listPagination = $this->taskManagementServices->listTestManagement($page, $size);

            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $result = $this->taskManagementServices->store($request->all());

            $answer = ($result)? ['error' => '', 'message' => 'Task Management created', 'result' => $result] : ['error' => 'Error, Player is not banned ','message' => ''];

            return $this->responseFactory->formattedJson($answer);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        try {
            $result = $this->taskManagementServices->update($request->all());

            $answer = ($result)? ['error' => '', 'message' => 'Task Management created', 'result' => $result] : ['error' => 'Error, Player is not banned ','message' => ''];

            return $this->responseFactory->formattedJson($answer);
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $this->taskManagementServices->destroy($id);

            return $this->responseFactory->messageSuccessJson();
        } catch(\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function dailyList(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson($this->taskManagementServices->dailyList());
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function companyList(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson($this->taskManagementServices->companyList());
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function itemList(): JsonResponse
    {
        try {
            return $this->responseFactory->formattedSTDClassJson($this->taskManagementServices->itemList());
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function imageUpload(BannerImageUploadRequest $request): JsonResponse
    {
        try {
            return $this->responseFactory->formattedJson([
                'url' => $this->taskManagementServices->imageUpload($request->file('image')),
            ]);
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRecord(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 15);
        $from = (string) $request->get('from', '');
        $to = (string) $request->get('to', '');

        try {
            $listPagination = $this->taskManagementServices->getRecord($page, $size, $from, $to);

            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails(Request $request): JsonResponse
    {
        $page = (int) $request->get('page', 1);
        $size = (int) $request->get('size', 15);
        $from = (string) $request->get('from', '');
        $to = (string) $request->get('to', '');
        $uid = (int) $request->get('uid', '');

        try {
            $listPagination = $this->taskManagementServices->getDetails($page, $size, $from, $to, $uid);

            return $this->responseFactory->paginatedJson(
                $listPagination->data->result,
                $listPagination->total,
            );
        } catch (\Throwable $e) {
            return $this->responseFactory->error($e->getMessage());
        }
    }
}
