<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Mobile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return ($value && preg_match_all('/^(\+98|0|)[9]{1}([0-9]{9,9}$)/i', $value));
//        return ($value && preg_match_all('/^[0]{1}[9]{1}([0-9]{9,9}$)/i', $value));
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('validation.Phone number format is wrong');
    }
}
