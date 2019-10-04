<?php

namespace App\Traits;

use Exception;

trait ValidatorTrait
{
    private $failMessage;

    /**
     * Check if input is valid
     *
     * @param string $ruleKey Key for rules property
     * @param string $input Input value
     * @return boolean
     */
    public function isValidInput(string $ruleKey, string $input)
    {
        if (! property_exists($this, 'rules')) {
            throw new Exception('rules property not exist in class: '. get_class($this));
        }

        $rule = $this->rules[$ruleKey] ?? null;

        /**
         * If there is no rules definition, just return true
         */
        if (is_null($rule)) {
            return true;
        }

        $rules = explode('|', $rule);

        foreach ($rules as $rule) {
            $ruleName = ucfirst($this->getRuleName($rule));
            $ruleValue = $this->getRuleValue($rule);

            $validatorResult = $this->{'validate'.$ruleName}($input, $ruleValue);

            if ($validatorResult === false) {
                $this->setFailMessage($ruleKey, $ruleName);
                return false;
            }
        };

        // Valid input can go here
        return true;
    }

    /**
     * Get rule name 
     * For example: The rule name for min:10 is "min"
     *
     * @param string $rule
     * @return string 
     */
    private function getRuleName($rule)
    {
        $length = strpos($rule, ':');

        return $length !== false ? substr($rule, 0, $length) : $rule;
    }

    /**
     * Get rule value 
     * For example: The rule value for min:10 is "10"
     *
     * @param string $rule
     * @return string 
     */
    private function getRuleValue($rule)
    {
        $length = strpos($rule, ':');

        return $length !== false ? substr($rule, $length+1, strlen($rule)) : null;
    }

    /**
     * Set fail message based on class definition
     *
     * @param string $input
     * @param string $rule
     * @return void
     */
    private function setFailMessage($input, $rule)
    {
        $key = strtolower($input . '.' . $rule);

        if (! property_exists($this, 'ruleMessages')) {
            throw new Exception('ruleMessages property not exist in class: '. get_class($this));
        }

        if (! array_key_exists($key, $this->ruleMessages)) {
            throw new Exception(sprintf('Please add error message in ruleMessages for %s key', $key));
        }

        $this->failMessage = $this->ruleMessages[$key];
    }

    private function validateString($input)
    { 
        return preg_match('/^([a-zA-Z\s]+)(?!([0-9]))$/', $input, $matches) > 0;
    }

    private function validateNumeric($input)
    {
        return preg_match('/^[0-9]+$/', $input, $matches) > 0;
    }

    private function validateMin($input, $min)
    {
        return $this->compareMinMax($input, $min, '>=');
    }

    private function validateMax($input, $min)
    {
        return $this->compareMinMax($input, $min, '<=');
    }

    private function compareMinMax($input, $value, $operator)
    {
        /**
         * For numeric, just compare it
         */
        if (is_numeric($input)) {
            eval('$compareResult = '. $input . $operator . $value . ';');
            return $compareResult;
        }

        /**
         * For array, count the array length and compare 
         */
        if (is_array($input)) {
            eval('$compareResult = '. count($input) . $operator . $value . ';');
            return $compareResult;
        }

        /**
         * For string, count the character length and compare 
         */
        eval('$compareResult = '. strlen($input) . $operator . $value . ';');
        return $compareResult;
    }
}
