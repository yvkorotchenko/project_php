<?php

namespace App\Laravue\Traits;

trait DefaultGuardNameApiTrait {
    public function __construct(array $attributes = [])
    {
        $attributes['guard_name'] = self::DEFAULT_GUARD_NAME;

        parent::__construct($attributes);
    }
}
