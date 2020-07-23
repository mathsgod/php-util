<?php

namespace PHP\Util;

use ArrayObject;

class OrderedQueryable extends Queryable implements IOrderedQueryable
{

    public function getIterator()
    {
        $orderBy = null;

        $source = $this->source;
        foreach ($this->expression as $expression) {
            switch ($expression["type"]) {
                case "orderBy":
                    $orderBy = $expression["params"]["key_selector"];
                    $source = self::Execute($source, $expression);
                    break;
                case "thenBy":
                    $arr = iterator_to_array($source);
                    $key_selector = $expression["params"]["key_selector"];
                    usort($arr, function ($a, $b) use ($key_selector, $orderBy) {
                        if ($orderBy) {
                            $a_value = $orderBy($a);
                            $b_value = $orderBy($b);
                            if ($a_value === $b_value) {
                                $a_value = $key_selector($a);
                                $b_value = $key_selector($b);
                                return $a_value <=> $b_value;
                            }
                            return $a_value <=> $b_value;
                        } else {
                            $a_value = $key_selector($a);
                            $b_value = $key_selector($b);
                            return $a_value <=> $b_value;
                        }
                    });

                    $source = new ArrayObject($arr);

                    break;
                default:
                    $source = self::Execute($source, $expression);
                    break;
            }
        }

        return $source;
    }

    public function thenBy(callable $key_selector): IOrderedQueryable
    {

        $exp = $this->expression;
        $exp[] = [
            "type" => "thenBy",
            "params" => [
                "key_selector" => $key_selector
            ]
        ];

        return $this->createQuery($exp);
    }
}
