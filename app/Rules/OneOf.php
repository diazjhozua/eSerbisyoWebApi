<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;

class OneOf implements Rule
{
    public $oneOf = [];
    public $request = null;
    public $message = "";

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Request $request, array $oneOf, string $message = "")
    {
    $this->oneOf = $oneOf;
    $this->request = $request;
    $this->message = $message;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
    $count = 0;
    foreach ($this->oneOf as $param) {
        if($this->request->has($param)){
        $count++;
        }
    }
    return count($this->oneOf) && ($count === 1) ? true : false;

    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
    $json_encodedList = json_encode($this->oneOf);

    return  strlen(trim($this->message)) ? $this->message : "Please insert one of $json_encodedList.";
    }
}
