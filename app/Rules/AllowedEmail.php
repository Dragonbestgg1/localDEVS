<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class AllowedEmail implements Rule
{
    protected $domain;

    public function __construct($domain)
    {
        $this->domain = $domain;
    }

    public function passes($attribute, $value)
    {
        return strpos($value, $this->domain) !== false;
    }

    public function message()
    {
        return 'The email address must contain ' . $this->domain;
    }
}
