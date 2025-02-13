<?php

declare(strict_types=1);

namespace App\Laravue\Validators;

use App\Laravue\Exceptions\ValidationException as AppValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ArrayValidator
{
    public function __construct(
        private array $rules
    ) {}

    /**
     * @throws AppValidationException
     */
    public function validate(array $input): void
    {
        $validator = Validator::make($input, $this->rules);
        try {
            $validator->validate();
        } catch (ValidationException $e) {
            throw new AppValidationException(
                $e->getMessage(),
                $validator->errors()
            );
        }
    }
}