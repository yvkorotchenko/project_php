<?php

namespace App\Chat\Repositories;

use App\Chat\Models\CustomerServiceOperator;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class CustomerServiceOperatorRepository
{
    private const CONNECTION_NAME = 'mysql';
    private const TABLE_NAME = 'customer_service_operators';

    public function operatorById(int $operatorId): CustomerServiceOperator
    {
        return CustomerServiceOperator::find($operatorId);
    }

    public function findAll(int $limit = 0, int $offset = 0): array
    {
        $query = $this->qb();
        if ($limit > 0) {
            $query->limit($limit)->offset($offset);
        }
        $rows = $query->get();

        $result = [];
        foreach ($rows as $row) {
            $result[] = $this->mapRowToEntity($row);
        }

        return $result;
    }

    public function countAll(): int
    {
        return $this->qb()->count();
    }

    private function qb(): Builder
    {
        return DB::connection(self::CONNECTION_NAME)->table(self::TABLE_NAME);
    }

    private function mapRowToEntity(object $row): CustomerServiceOperator
    {
        $operator = new CustomerServiceOperator([
            $row->id,
            $row->name,
            $row->customer_service_id,
            $row->created_at,
            $row->updated_at,
            $row->email,
            $row->password
        ]);

        return $operator;
    }
}
