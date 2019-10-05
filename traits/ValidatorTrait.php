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
    private function getRuleName(string $rule)
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
    private function getRuleValue(string $rule)
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

    /**
     * Validation to ensure a string (Alphabet) with no numbers
     *
     * @param string $input
     * @return boolean
     */
    private function validateString($input)
    { 
        return preg_match('/^([a-zA-Z\s]+)(?!([0-9]))$/', $input, $matches) > 0;
    }

    /**
     * Validation to ensure a numeric
     *
     * @param string $input
     * @return boolean
     */
    private function validateNumeric($input)
    {
        return preg_match('/^[0-9]+$/', $input, $matches) > 0;
    }

    /**
     * Validation to check input not less than minimum value
     *
     * @param string $input
     * @param array|string $min
     * @return boolean
     */
    private function validateMin(string $input, $min)
    {
        return $this->compareMinMax($input, $min, '>=');
    }

    /**
     * Validation to check input not greater than maximum value
     *
     * @param string $input
     * @param array|string $max
     * @return boolean
     */
    private function validateMax(string $input, $max)
    {
        return $this->compareMinMax($input, $max, '<=');
    }

    /**
     * Compare input with standard value based on operator
     *
     * @param string $input
     * @param array|string $value
     * @param string $operator
     * @return boolan
     */
    private function compareMinMax(string $input, $value, string $operator)
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

    /**
     * Validation to ensure input is in a list
     *
     * @param string $input
     * @param string $value
     * @return boolean
     */
    private function validateList(string $input, string $value)
    {
        $list = explode(',', $value);

        return in_array($input, $list);
    }
}
