<?php
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Validator;

// See: https://gist.github.com/vmosoti/8ef268588cbdbf33f263e4733571254b

class CommaSeparatedEmails implements Rule
{

    /**
     * Generate an example value that satisifies the validation rule.
     *
     * @param none.
     * @return string.
     *
     **/
    public function example()
    {
        return 'name1@site.com,name2@site.com';
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
        $val = str_replace(' ', '', str_replace(';', ',', $value));

        return !Validator::make(
            [
                "{$attribute}" => explode(',', $val)
            ],
            [
                "{$attribute}.*" => 'required|email'
            ]
        )->fails();
    }


    /**
     * Get the validation error message.
     *
     * @param none.
     * @return string.
     *
     **/
    public function message()
    {
        return l(
            'The :attribute must have valid email addresses, e.g. ',
            'validationrules'
        ) . $this->example();
    }

}