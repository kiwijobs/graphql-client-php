<?php

namespace GraphQLClient;

class Query extends Field
{
    private $params;

    public function __construct(string $name, array $params = [], array $fields = [])
    {
        parent::__construct($name, $fields);
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->params;
    }

    /**
     * @param array|Variable[] $variables
     * @return string
     */
    public function getQueryHeader(array $variables)
    {
        if (!count($variables)) {
            return '';
        }

        $variableKey = 0;
        $result = 'Header(';
        foreach($variables as $key => $variable) {
            $result .= $variableKey > 0 ? ' ' : '';
            $result .= '$'. $key . ': ' . $variable->getType();
            $variableKey++;
        }

        $result .= ')';

        return $result;
    }
}
