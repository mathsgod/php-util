<?php

namespace PHP\Util;

use ArrayObject;
use Traversable;

class OrderedQueryable extends Queryable implements IOrderedQueryable
{

    public function getIterator(): Traversable
    {
        $order_expression = null;

        $source = $this->source;
        foreach ($this->expression as $expression) {
            switch ($expression["type"]) {
                case "orderBy":
                case "orderByDescending":
                    $order_expression = $expression;
                    $source = self::Execute($source, $expression);
                    break;
                case "thenBy":
                    $arr = iterator_to_array($source);
                    $key_selector = $expression["params"]["key_selector"];
                    usort($arr, function ($a, $b) use ($key_selector, $order_expression) {
                        if ($order_expression) {
                            $order_key_selector = $order_expression["params"]["key_selector"];
                            $a_value = $order_key_selector($a);
                            $b_value = $order_key_selector($b);
                            if ($a_value != $b_value) {
                                if ($order_expression["type"] == "orderBy") {
                                    return $a_value <=> $b_value;
                                } elseif ($order_expression["type"] == "orderByDescending") {
                                    return $b_value <=> $a_value;
                                }
                            }
                        }
                        $a_value = $key_selector($a);
                        $b_value = $key_selector($b);
                        return $a_value <=> $b_value;
                    });

                    $source = new ArrayObject($arr);

                    break;
                case "thenByDescending":
                    $arr = iterator_to_array($source);
                    $key_selector = $expression["params"]["key_selector"];
                    usort($arr, function ($a, $b) use ($key_selector, $order_expression) {
                        if ($order_expression) {
                            $order_key_selector = $order_expression["params"]["key_selector"];
                            $a_value = $order_key_selector($a);
                            $b_value = $order_key_selector($b);
                            if ($a_value != $b_value) {
                                if ($order_expression["type"] == "orderBy") {
                                    return $a_value <=> $b_value;
                                } elseif ($order_expression["type"] == "orderByDescending") {
                                    return $b_value <=> $a_value;
                                }
                            }
                        }
                        $a_value = $key_selector($a);
                        $b_value = $key_selector($b);
                        return $b_value <=> $a_value;
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

    public function thenByDescending(callable $key_selector): IOrderedQueryable
    {
        $exp = $this->expression;
        $exp[] = [
            "type" => "thenByDescending",
            "params" => [
                "key_selector" => $key_selector
            ]
        ];

        return $this->createQuery($exp);
    }
}
