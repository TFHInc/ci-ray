<?php
if (!function_exists('ray')) {
    /**
     * Return an instance of the Ray Library.
     *
     * @return Ray
     */
    function ray(array $working_array): \TFHInc\Ray\Ray
    {
        $CI =& get_instance();

        if (!isset($CI->ray)) {
            $CI->ray = new TFHInc\Ray\Ray();
        } elseif (!$CI->ray instanceof Ray) {
            $CI->ray = new TFHInc\Ray\Ray();
        }

        return $CI->ray->with($working_array);
    }
}