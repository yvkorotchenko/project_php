<?php
//declare(strict_types=1);

namespace App\Laravue\Services;

use App\MtSports\Repositories\MemberInformationModificationRepository;

class MemberInformationModificationService
{
    public function __construct(
        private MemberInformationModificationRepository $memberInformationModificationRepository,
    ) {
    }

    public function index(int $id)
    {
        return $this->memberInformationModificationRepository->member($id);
    }

    public function update(array $memberData)
    {
        return $this->memberInformationModificationRepository->updateMember($memberData);
    }

    public function info(string $fieldName, string $fieldValue)
    {
        return $this->memberInformationModificationRepository->getPlayerInfo($fieldName, $fieldValue);
    }

    public function getMemberPlayerInfo(string $fieldName, string $fieldValue)
    {
        return $this->memberInformationModificationRepository->getMemberPlayerInfo($fieldName, $fieldValue);
    }

    public function updateMemberInfo($objMemberInfo)
    {
        $this->memberInformationModificationRepository->updateMemberInfo($objMemberInfo);
    }

    public function updateRemark(int $uid, string $remark)
    {
        $this->memberInformationModificationRepository->updateRemark($uid, $remark);
    }
}
