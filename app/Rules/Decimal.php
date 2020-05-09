<?php
// Credits: https://github.com/alphametric/laravel-validation-rules
// https://laraveldaily.com/40-additional-laravel-validation-rules/

// https://code.tutsplus.com/es/tutorials/how-to-create-a-laravel-helper--cms-28537

// Namespace
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

// Decimal rule
class Decimal implements Rule
{
	private $nleft;     // Whole number part digits
    private $nright;    // Decimal part digits

    public function __construct($nleft = null, $nright = null)
    {
        $this->nleft  = $nleft  !== null ? $nleft  : (20-6);    // Migrations defaults
        $this->nright = $nright !== null ? $nright : 6;
    }

	/**
	 * Generate an example value that satisifies the validation rule.
	 *
	 * @param none.
	 * @return string.
	 *
	 **/
	public function example()
	{
		return mt_rand(1, (int) str_repeat('9', $this->nleft)) . '.' .
			   mt_rand(1, (int) str_repeat('9', $this->nright));
	}



    /**
     * Determine if the validation rule passes.
     *
     * The rule has two parameters:
     * 1. The maximum number of digits before the decimal point.
     * 2. The maximum number of digits after the decimal point.
     *
     * @param string $attribute.
     * @param mixed $value.
     * @return bool.
     *
     **/
    public function passes($attribute, $value)
    {
        return preg_match(
            "/^[-+]?[0-9]{1,{$this->nleft}}(\.[0-9]{1,{$this->nright}})$/", $value
        );
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
            'The :attribute must be an appropriately formatted decimal e.g. ',
            'validationrules'
        ) . $this->example();
    }

}