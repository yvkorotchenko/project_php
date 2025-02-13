<?php

declare(strict_types=1);

namespace App\MtSports\Repositories;

use App\MtSports\Models\Client;
use Carbon\Carbon;

class ClientsRepository
{
    public function clientById(int $id): ?Client
    {
        return Client::find($id);
    }

    public function countClientsByDate(Carbon $date): int
    {
        return Client::whereDate()->count();
    }
}
