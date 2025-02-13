<?php
declare(strict_types=1);

namespace App\Laravue\Services;

use App\Laravue\Models\Avatars;
use App\Laravue\Traits\UploadImage;

class AvatarService
{
    public function __construct(
        private StaticStorageService $staticStorageService,
    ) {}

    use UploadImage;

    public function listPagination(int $size): array
    {
        $avatars = Avatars::query()->paginate($size)->toArray();

        for ($i = 0, $len = count($avatars['data']); $len > $i; $i++) {
            $avatars['data'][$i]['visible'] = $avatars['data'][$i]['visible'] === '1'? true : false;
        }

        return $avatars;
    }

    public function create(array $avatar): void
    {
        Avatars::create([
            'name' => $avatar['name'],
            'img_url' => $avatar['img_url'],
            'position' => $avatar['position'],
            'visible' => $avatar['visible'],
        ]);
    }

    public function update(int $id, array $avatar): void
    {
       $updateAvatar = Avatars::find($id);

       if ($updateAvatar) {
           $updateAvatar->name = $avatar['name'];
           $updateAvatar->img_url = $avatar['img_url'];
           $updateAvatar->position = $avatar['position'];
           $updateAvatar->visible = ($avatar['visible']) ? '1' : '0';

           $updateAvatar->save();
       } else {
           throw new \Exception('There is no such avatar in the database!!!');
       }
    }

    public function delete(string $ids, array $links): array
    {
        $listIds = \explode(',', $ids);

        try {
            if ($ids !== '0') {
                Avatars::destroy($listIds);
            }

            $this->staticStorageService->deleteImages($links);

            return [
                'success' => 1,
            ];
        } catch (\Throwable $e) {
            return [
                'success' => 0,
                'message' => 'The avatar has not been deleted. Perhaps there is no such avatar or the picture was deleted earlier!',
            ];
        }
    }
}