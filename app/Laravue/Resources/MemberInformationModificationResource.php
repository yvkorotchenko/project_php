<?php

namespace App\Laravue\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class MemberInformationModificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            "apple_account" => $this->apple_account,
            "avatar" => $this->avatar,
            "balance" => $this->balance,
            "country_code" => $this->country_code,
            "created" => $this->created,
            "device_id" => $this->device_id,
            "email" => $this->email,
            "facebook_account" => $this->facebook_account,
            "google_account" => $this->google_account,
            "grade" => $this->grade,
            "id" => $this->id,
            "is_enable" => $this->is_enable,
            "last_login_ip" => $this->last_login_ip,
            "last_login_time" => Carbon::createFromTimestamp($this->last_login_time)->toDateTimeString(),
            "limit_amount" => $this->limit_amount,
            "nickname" => $this->nickname,
            "phone" => $this->phone,
            "pid" => $this->pid,
            "reg_ip" => $this->reg_ip,
            "sex" => $this->sex,
            "typ" => $this->typ,
            "updated_at" => Carbon::createFromTimestamp($this->updated_at)->toDateTimeString(),
            "vip_level" => $this->vip_level,
        ];
    }
}
