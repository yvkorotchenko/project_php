<?php

namespace App\Laravue\Services;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Laravue\Models\Icons;
use Ramsey\Collection\Collection;

class IconsService extends DefaultSettingService
{
    public function list(array $limitPage): LengthAwarePaginator
    {
        $iconsQuery = Icons::query();

        if (\array_key_exists('limit', $limitPage)) {
            $limit = $limitPage['limit'];
        } else {
            $limit = self::ITEMS_PER_PAGE_DEFAULT;
        }

        return $iconsQuery->paginate($limit);
    }

    public function all(): Collection
    {
        return Icons::all();
    }

    public function create(array $icon): Icons
    {
        $icons = new Icons([
            'unicode' => $icon['unicode'],
            'class_name' => $icon['class_name'],
            'name' => $icon['name'],
            'sort' => $icon['sort'],
        ]);

        $icons->save();
        $icons->refresh();

        return $icons;
    }

    public function update(array $iconData, Icons $icon): Icons
    {
        $icon->fill([
            'unicode' => $iconData['unicode'],
            'class_name' => $iconData['class_name'],
            'name' => $iconData['name'],
            'sort' => $iconData['sort'],
        ]);

        $icon->save();

        return $icon;
    }

    public function delete(int $iconId): bool
    {
        $icon = Icons::find($iconId);

        if ($iconId === null) {
            return false;
        } else {
            return $icon->delete();
        }
    }

    public function destroyList(array $iconListIds): bool
    {
        return Icons::whereIn('id', $iconListIds['ids'])->delete();
    }
}