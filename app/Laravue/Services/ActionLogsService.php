<?php

declare(strict_types=1);

namespace App\Laravue\Services;

use App\Laravue\Models\ActionLog;
use App\Laravue\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

class ActionLogsService
{
    public function listPagination(int $size, string $search): LengthAwarePaginator
    {
        $filters = json_decode($search, true, JSON_THROW_ON_ERROR);

        $actionLog = ActionLog::query();

        if (isset($filters['dateStart']) && isset($filters['dateEnd'])) {
            $dateStart = $filters['dateStart'] . ' 00:00:00';
            $dateEnd = $filters['dateEnd'] . ' 23:59:59';

            $actionLog->whereBetween('created_at', [
                $dateStart,
                $dateEnd,
            ]);
        }
        else {
            if (isset($filters['dateStart'])) {
                $actionLog->where('created_at','LIKE', '%' . $filters['dateStart'] . '%',
                );
            }

            if (isset($filters['dateEnd'])) {
                $actionLog->where('created_at', 'LIKE',
                    '%' . $filters['dateEnd'] . '%',
                );
            }
        }

        if (isset($filters['action'])) {
            $actionLog->where('action_en', $filters['action']);
        }

        if (isset($filters['userId'])) {
            $actionLog->where('user_id', '=', $filters['userId']);
        }
        $actionLog->orderBy('id', 'desc');

        return $actionLog->paginate($size);
    }

    public function allUsers(): array
    {
        return User::all()->toArray();
    }
}
