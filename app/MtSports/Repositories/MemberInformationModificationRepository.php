<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\Laravue\Entities\MemberInformationModification;
use App\MtSports\Services\MtSportsClient;
use App\MtSports\Services\MtSportsResponseParser;
use App\Laravue\Entities\MemberInfoEntities;

class MemberInformationModificationRepository
{
    private const BASE_PATH = '/crud/users/%d';
    private const BASE_PATH_USER_INFO = '/crud/users/info/userInfo';
    private const BASE_PATH_INFO = '/crud/users/search/detailed';
    private const BASE_MEMBER_INFO_PATH = '/crud/users/member-info';
    private const BASE_USER_INFO_PLAYER = '/users/info/player';

    public function __construct(
        private MtSportsClient $client,
        private MtSportsResponseParser $responseParser,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function member(int $id): object
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(
                \sprintf(self::BASE_PATH, $id)
            )
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    public function updateMember(array $member): void
    {
        $update = new MemberInformationModification($member);
        $responseCode = $this->responseParser->statusCode(
            $this->client->put(
                \sprintf(self::BASE_PATH, $member['id']),
                [],
                $update
            )
        );

        if ($responseCode !== 200) {
            throw new \Exception('Unsuccessfull response code: ' . $responseCode);
        }
    }

    /**
     * @throws \JsonException
     */
    public function getPlayerInfo(string $fieldName, string $fieldValue): object
    {
        $responseBody = $this->responseParser->body(
            $this->client->get('/crud/users/info/userInfo', [
                "fieldName" => $fieldName,
                "fieldValue" => $fieldValue
            ])
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    /**
     * @throws \JsonException
     */
    public function getMemberPlayerInfo(string $fieldName, string $fieldValue): object
    {
        $responseBody = $this->responseParser->body(
            $this->client->get(self::BASE_USER_INFO_PLAYER, [
                "fieldName" => $fieldName,
                "fieldValue" => $fieldValue
            ])
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }

        return $responseBody->data;
    }

    /**
     * @throws \JsonException
     */
    public function updateMemberInfo($objMemberInfo): void
    {
        $update = new MemberInfoEntities(
            $objMemberInfo["uid"],
            $objMemberInfo["nickname"],
            $objMemberInfo["phone"],
            $objMemberInfo["email"],
            $objMemberInfo["effectiveBet"],
            $objMemberInfo["withdrawStandard"],
            $objMemberInfo['countryCode'],
        );
        $responseBody = $this->responseParser->body(
            $this->client->put(
                self::BASE_MEMBER_INFO_PATH,
                [],
                $update
            )
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }

    public function updateRemark(int $uid, string $remark): void
    {
        $responseBody = $this->responseParser->body(
            $this->client->post(
                '/crud/users/info/updateRemark',
                [],
                null,
                ['id' => $uid, 'remarks' => $remark]
            )
        );

        if ($responseBody->code !== '200') {
            throw new \Exception($responseBody->message);
        }
    }
}
