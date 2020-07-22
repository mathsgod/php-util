<?php

namespace PHP\Util;

use IteratorAggregate;

interface IQueryable extends IteratorAggregate
{
    /**
     * 判斷序列的所有項目是否全都符合條件。
     */
    public function all(callable $callback): bool;

    /**
     * 判斷序列的任何項目是否符合條件。
     */
    public function any(callable $callback): bool;

    /**
     * 計算數值序列的平均值。
     */
    public function average();

    /**
     * 判斷是否包含指定的項目。
     */
    public function contains($item): bool;

    /**
     * 計算數值序列的總和。
     */
    public function sum();

    /**
     * 傳回序列中的項目數。
     */
    public function count(): int;

    /**
     * 從序列傳回獨特的項目。
     */
    public function distinct(): IQueryable;

    /**
     * 傳回序列的第一個項目。
     */
    public function first();

    /**
     * 傳回序列中的最後一個項目。
     */

    public function last();

    /**
     * 依遞增順序排序序列中的項目。
     */
    public function orderBy(callable $callback): IQueryable;

    /**
     * 依遞減順序排序序列中的項目。
     */
    public function orderByDescending(callable $callback): IQueryable;

    /**
     * 傳回新的可查詢序列，包含來自 source 的元素，並在開頭加上指定的 element。
     */
    public function prepend($element): IQueryable;

    /**
     * 反轉序列中項目的排序方向。
     */
    public function reverse(): IQueryable;

    public function min(callable $callback);
    public function max(callable $callback);


    /**
     * 將序列的每個元素規劃成一個新的表單。
     */
    public function select(callable $callback): IQueryable;
}