<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Laravue\Models\Avatars;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nick' => $this->nick,
            'ip' => $this->ip,
            'roles' => \array_map(
                function ($role) {
                    return $role['name'];
                },
                $this->roles->toArray()
            ),
            'permissions' => \array_map(
                function ($permission) {
                    return $permission['name'];
                },
                $this->getAllPermissions()->toArray()
            ),
            // 'avatar' => 'https://i.pravatar.cc',
            'avatar' => \call_user_func(function() {
                $defaultAvatar = (object) [ 'name' => 'Default', 'img_url' => '/avatars/main.png'];
                $avatar = is_null(Avatars::find($this->avatar)) ? $defaultAvatar : Avatars::find($this->avatar);

                return [ 'name' => $avatar->name, 'img' => $avatar->img_url];
            }),
            'create' => $this->created_at->toDateTimeString(),
            'update' => $this->updated_at->toDateTimeString(),
            ];
    }
}
