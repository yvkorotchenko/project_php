<?php

namespace App\Chat\Services;

use App\Chat\Entities\PaginatedCollection;
use App\Chat\Models\CustomerServiceOperator;
use App\Chat\Repositories\CustomerServiceOperatorRepository;

class CustomerServiceOperatorService
{
    public function paginated(int $page, int $perPage): PaginatedCollection
    {
        $paginated = CustomerServiceOperator::paginate($perPage, ['*'], 'page', $page);

        return new PaginatedCollection(
            $paginated->items(),
            $paginated->total(),
            $page
        );
    }

}