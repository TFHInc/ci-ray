<?php

namespace TFHInc\Ray;

/**
 * Ray
 *
 * An expressive PHP array support class.
 *
 * @package Ray
 * @author Colin Rafuse <colin.rafuse@gmail.com>
 */
class Ray {
    /**
     * @var array
     */
    private $working_array;

    /**
     * Create an instance of Ray.
     *
     * @return  Ray
     */
    public function __construct()
    {
        $this->working_array = [];

        return $this;
    }

/*
|--------------------------------------------------------------------------
| Ray Support Methods
|--------------------------------------------------------------------------
|
| The Ray support methods.
|
*/

    /**
     * Set the Ray instances working_array property.
     *
     * @param   array    $working_array
     * @return  Ray      $this
     */
    private function setWorkingArray(array $working_array): \TFHInc\Ray\Ray
    {
        $this->working_array = $working_array;

        return $this;
    }

    /**
     * Flush the existing working_array property and set a new one.
     *
     * @param   array    $working_array
     * @return  Ray      $this
     */
    public function with(array $working_array): \TFHInc\Ray\Ray
    {
        $this->flush();
        $this->setWorkingArray($working_array);

        return $this;
    }

    /**
     * Flush the working_array property.
     *
     * @return  Ray      $this
     */
    public function flush(): \TFHInc\Ray\Ray
    {
        unset($this->working_array);
        $this->setWorkingArray([]);

        return $this;
    }

    /**
     * Return the working array and then flush the working_array property.
     *
     * @return  Array   $return_array
     */
    public function toArray(): array
    {
        $return_array = $this->working_array;
        $this->flush();

        return $return_array;
    }

/*
|--------------------------------------------------------------------------
| Ray Manipulation Methods
|--------------------------------------------------------------------------
|
| The Ray manipulation methods.
|
*/

    /**
     * Sort the working array property by it's values.
     *
     * @return  Ray     $this
     */
    public function sortByValues(): \TFHInc\Ray\Ray
    {
        asort($this->working_array);

        return $this;
    }

    /**
     * Sort the working array property by it's keys.
     *
     * @return  Ray     $this
     */
    public function sortByKeys(): \TFHInc\Ray\Ray
    {
        ksort($this->working_array);

        return $this;
    }

    /**
     * Determine if the working array contains a value. Optionally provide a key to limit the contains() check.
     *
     * @param   array       $contains_params
     * @return  bool        $contains_bool
     */
    public function contains(string ...$contains_params): bool
    {
        $contains_bool = false;

        if (count($contains_params) === 1) {
            array_walk_recursive($this->working_array, function ($item, $key) use (&$contains_bool, $contains_params) {
                if ($item === $contains_params[0]) {
                    $contains_bool = true;
                    return;
                }
            });
        }
        else {
            array_walk_recursive($this->working_array, function ($item, $key) use (&$contains_bool, $contains_params) {
                if ($key === $contains_params[0] && $item === $contains_params[1]) {
                    $contains_bool = true;
                    return;
                }
            });
        }

        $this->flush();

        return $contains_bool;
    }

    /**
     * Determine if the working array contains a key.
     *
     * @param   string      $has_key
     * @return  bool        $has_bool
     */
    public function has(string $has_key): bool
    {
        $has_bool = false;

        array_walk_recursive($this->working_array, function ($item, $key) use (&$has_bool, $has_key) {
            if ($key === $has_key) {
                $has_bool = true;
                return;
            }
        });

        $this->flush();

        return $has_bool;
    }

    /**
     * Sum a "column" in the working array.
     *
     * @param   string|null     $sum_key
     * @return  mixed           $sum_numeric
     */
    public function sum(?string $sum_key = null)
    {
        $sum_numeric = 0;

        if (is_null($sum_key)) {
            $sum_numeric = array_sum($this->working_array);
        } else {
            array_walk_recursive($this->working_array, function ($item, $key) use(&$sum_numeric, $sum_key) {
                if ($key === $sum_key) {
                    $sum_numeric += $item;
                }
            });
        }

        $this->flush();

        return $sum_numeric;
    }

    /**
     * Get the average of a "column" in the working array.
     *
     * @param   string|null     $avg_key
     * @return  mixed           $avg_numeric
     */
    public function avg(?string $avg_key = null)
    {
        $avg_numeric = 0;

        if (is_null( $avg_key )) {
            $avg_numeric = array_sum($this->working_array) / count($this->working_array);
        } else {
            array_walk_recursive($this->working_array, function ($item, $key) use(&$temp_sum, &$temp_count , $avg_key) {
                if ($key ===  $avg_key) {
                    $temp_sum += $item;
                    $temp_count++;
                }
            });

            $avg_numeric = ($temp_sum / $temp_count);
        }

        $this->flush();

        return $avg_numeric;
    }

    /**
     * Count the items in the working array.
     *
     * @return  mixed
     */
    public function count(): int
    {
        return count($this->working_array);
    }

    /**
     * Return the first array value.
     *
     * @return  mixed
     */
    public function first()
    {
        return array_shift($this->working_array);
    }

    /**
     * Return the last array value.
     *
     * @return  mixed
     */
    public function last()
    {
        return array_values(array_slice($this->working_array, -1))[0];
    }

    /**
     * Return the working arrays values. Can be used to reindex the array with consecutive integers.
     *
     * @return  Ray     $this
     */
    public function values(): \TFHInc\Ray\Ray
    {
        $values_array = array_values($this->working_array);

        $this->setWorkingArray($values_array);

        return $this;
    }

    /**
     * Limit the working array by excluding an array of keys.
     *
     * @param   array   $except_key
     * @return  Ray     $this
     */
    public function except(array $except_key): \TFHInc\Ray\Ray
    {
        foreach ($except_key as $key) {
            unset($this->working_array[$key]); // TODO: Do we want to reindex the working array?
        }

        return $this;
    }

    /**
     * Limit the working array by only including an array of keys.
     *
     * @param   array   $only_key
     * @return  Ray     $this
     */
    public function only(array $only_key): \TFHInc\Ray\Ray
    {
        foreach ($this->working_array as $key => $value) {
            if (!in_array($key, $only_key)) {
                unset($this->working_array[$key]); // TODO: Do we want to reindex the working array?
            }
        }

        return $this;
    }

    /**
     * Limit the working array by unique value. The array keys are preserved. If there are duplicate values, the first
     * key/value pair will be retained.
     *
     * @param   string  $unique_key
     * @return  Ray     $this
     */
    public function unique(?string $unique_key = null): \TFHInc\Ray\Ray
    {
        $unique_array = [];
        $temp_array = [];

        if ($this->isMultiDimensional($this->working_array) && !is_null($unique_key) ) {
            foreach ($this->working_array as $key => $value) {
                if (is_array($value)) {
                    if (!in_array($value[$unique_key], $temp_array)) {
                        $temp_array[] = $value[$unique_key];
                        $unique_array[$key] = $value;
                    }
                }
            }
        } else {
            $unique_array = array_unique($this->working_array, SORT_REGULAR);
        }

        $this->setWorkingArray($unique_array);

        return $this;
    }

    /**
     * Group (key) the working array by a specific keys value.
     *
     * @param   string  $group_by_key
     * @return  Ray     $this
     */
    public function groupBy(string $group_by_key): \TFHInc\Ray\Ray
    {
        $keyed_array = [];

        foreach ($this->working_array as $key => $value) {
            if (is_array($value)) {
                $keyed_array[$value[$group_by_key]][] = $value;
            } else {
                // TODO: exception - value must be an array
            }
        }

        $this->setWorkingArray($keyed_array);

        return $this;
    }

    /**
     * Retreive an entire column from the working array. Optionally key the new transformed array by the provided key_by.
     *
     * @param   string      $column_key
     * @param   string|null $column_key_by
     * @return  Ray         $this
     */
    public function column(string $column_key, ?string $column_key_by = null): \TFHInc\Ray\Ray
    {
        $column_array = [];

        if (is_null($column_key_by)) {
            $column_array = array_column($this->working_array, $column_key);
        } else {
            $column_array = array_column($this->working_array, $column_key, $column_key_by);
        }

        $this->setWorkingArray($column_array);

        return $this;
    }

    /**
     * Limit the working array by a specific key and value combination.
     *
     * @param   string      $where_key
     * @param   string      $where_value
     * @return  Ray         $this
     */
    public function where(string $where_key, string $where_value): \TFHInc\Ray\Ray
    {
        $where_array = [];

        foreach ($this->working_array as $key => $value ) {
            if (is_array($value)) {
                if (array_key_exists($where_key, $value)) {
                    if ($value[$where_key] === $where_value) {
                        $where_array[$key] = $value;
                    }
                }
            }
        }

        $this->setWorkingArray($where_array);

        return $this;
    }

    /**
     * Limit the working array by a specific key and an array of possible values.
     *
     * @param   string      $where_key
     * @param   array       $where_values
     * @return  Ray         $this
     */
    public function whereIn(string $where_key, array $where_values): \TFHInc\Ray\Ray
    {
        $where_array = [];

        foreach ($this->working_array as $key => $value) {
            if (is_array($value )) {
                if (array_key_exists( $where_key, $value)) {
                    if (in_array($value[$where_key], $where_values)) {
                        $where_array[$key] = $value;
                    }
                }
            }
        }

        $this->setWorkingArray($where_array);

        return $this;
    }

    /**
     * Limit the working array by a specific key and value combination.
     *
     * @param   string      $where_key
     * @param   string      $where_value
     * @return  Ray         $this
     */
    public function whereNot(string $where_key, string $where_value): \TFHInc\Ray\Ray
    {
        $where_array = [];

        foreach ($this->working_array as $key => $value) {
            if (is_array($value)) {
                if (array_key_exists($where_key, $value)) {
                    if ($value[$where_key] !== $where_value) {
                        $where_array[$key] = $value;
                    }
                }
            }
        }

        $this->setWorkingArray($where_array);

        return $this;
    }

    /**
     * Limit the working array by a specific key and an array of possible values.
     *
     * @param   string      $where_key
     * @param   array       $where_values
     * @return  Ray         $this
     */
    public function whereNotIn(string $where_key, array $where_values): \TFHInc\Ray\Ray
    {
        $where_array = [];

        foreach ($this->working_array as $key => $value) {
            if (is_array($value)) {
                if (array_key_exists($where_key, $value)) {
                    if (!in_array($value[$where_key], $where_values)) {
                        $where_array[$key] = $value;
                    }
                }
            }
        }

        $this->setWorkingArray($where_array);

        return $this;
    }

    /**
     * Filter the working array by a callback.
     *
     * @param   callable    $filter_callable
     * @return  Ray         $this
     */
    public function filter(callable $filter_callable): \TFHInc\Ray\Ray
    {
        $filter_array = array_filter($this->working_array, $filter_callable, ARRAY_FILTER_USE_BOTH);

        $this->setWorkingArray($filter_array);

        return $this;
    }

    /**
     * Reduce the working array by a callback to a single value.
     *
     * @param   callable    $reduce_callable
     * @return  mixed       $reduce_numeric
     */
    public function reduce(callable $reduce_callable)
    {
        $reduce_numeric = array_reduce($this->working_array, $reduce_callable, null);

        $this->flush();

        return $reduce_numeric;
    }

/*
|--------------------------------------------------------------------------
| Internal Utilities
|--------------------------------------------------------------------------
|
| Internal utility methods.
|
*/

    /**
     * Determine if the array is multi-dimensional.
     *
     * @param   array       $array
     * @return  bool        $is_multi_dimensional
     */
    private function isMultiDimensional(array $array): bool
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                return true;
            }
        }

        return false;
    }
}
