<?php

namespace App\Contracts;

abstract class DisplayPrompt
{
    public function run($reqParams = [])
    {
        if (! property_exists($this, 'parameters')) {
            throw new Exception('parameters property not exist in class: '. get_class($this));
        }

        if (! is_array($this->parameters)) {
            throw new Exception('The parameters property should be an array');
        }

        foreach($this->parameters as $param => $prompts) {
            if (array_key_exists($param, $reqParams)) continue;

            $input = readline($prompts);

            if ($this->isValidInput($param, $input)) {
                $reqParams[$param] = $input;
                continue;
            }

            echo $this->failMessage. PHP_EOL;

            return $this->run($reqParams);
        }

        return $this->processRequestParams($reqParams);
    }

    abstract public function processRequestParams(array $reqParams);
}
