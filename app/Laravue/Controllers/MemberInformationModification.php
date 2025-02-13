<?php

namespace App\Laravue\Controllers;

use App\Http\Controllers\Controller;
use App\Laravue\Services\MemberInformationModificationService;
use App\Laravue\Requests\MemberInformationModificationIndexRequest;
use App\Laravue\Requests\MemberInformationModificationUpdateRequest;
use App\Laravue\Resources\MemberInformationModificationResource;
use Illuminate\Http\Resources\Json\JsonResource;


class MemberInformationModification extends Controller
{
    public function __construct(
        private MemberInformationModificationService $memberInformationModificationService,
    ) {}

    public function index($id): JsonResource
    {
        return new MemberInformationModificationResource(
            $this->memberInformationModificationService->index($id)
        );
    }

    public function update(MemberInformationModificationUpdateRequest $request)
    {
        $this->memberInformationModificationService->update($request->all());
    }
}
