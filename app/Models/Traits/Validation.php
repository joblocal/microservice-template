<?php

namespace App\Models\Traits;

use Validator;

trait Validation
{
    /**
     * The Validator for this instance
     * @var [type]
     */
    private $validator = null;

    /**
     * Returns the instance's Validator
     * @param  [boolean] $recreate should the Validator be recreated?
     * @return [Validator] The Validator with all rules applied
     */
    public function getValidator($recreate = false)
    {
        if ($this->validator === null || $recreate) {
            $this->validator = Validator::make(
                $this->toArray(),
                $this->rules(),
                $this->validatorMessages()
            );
        }

        return $this->validator;
    }

    /**
     * Returns the validation rules
     * @return [array] The rules for this model (see: Illuminate\Validator\Validator:setRules)
     */
    public function rules()
    {
        return [];
    }

    /**
     * provides additional validation messages
     * @return [array] messages
     */
    public function validatorMessages()
    {
        return [
            'class' => 'The :attribute must be of type :class',
        ];
    }

    /**
     * Convenience method to check validity of the instance
     * @return boolean True if validation rules passed
     */
    public function isValid()
    {
        return $this->getValidator()->passes();
    }

    /**
     * Convenience method to get error messages
     * @return [Illuminate\Support\MessageBag] The error messages
     */
    public function errors()
    {
        return $this->getValidator()->errors();
    }

    /**
     * Conenvience method to check for errors on a specific attribute
     * @param  [string] $attribute The attribute's name
     * @return [boolean] True if an error message exists for the attribute
     */
    public function errorOn($attribute)
    {
        return $this->getValidator()->errors()->has($attribute);
    }
}
