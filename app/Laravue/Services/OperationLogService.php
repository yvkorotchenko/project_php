<?php

namespace App\Laravue\Services;

use App\Laravue\Models\OperationLog;
use Illuminate\Pagination\LengthAwarePaginator;

class OperationLogService extends DefaultSettingService
{
    public function list($limit, array $filters): LengthAwarePaginator
    {
        $queryLog = OperationLog::query();

        if (isset($filters['action_en'])) {
            $queryLog->where('action_en', 'LIKE', '%' . $filters['action_en'] . '%');
        }

        // find data
        if (isset($filters['start']) and isset($filters['end'])) {
            $queryLog->whereBetween('created_at', [
                $filters['start'] . ' 00:00:00',
                $filters['end'] . ' 23:59:59',
            ]);
        } elseif (isset($filters['start'])) {
            $queryLog->whereDate('created_at', '=', $filters['start'] . ' 00:00:00');
        }

        if (\array_key_exists('name', $filters) && '' !== $filters['name'] && null !== $filters['name']) {
            $queryLog->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }

        if (\array_key_exists('searchParam', $filters) && null !== $filters['searchParam']) {
            foreach (['name', 'action_en', 'type_req', 'ip', 'path_req'] as $fieldName) {
                $queryLog->orWhere($fieldName, 'LIKE', $filters['searchParam'] . '%');
            }
        }

        return $queryLog->paginate($limit);
    }
}
