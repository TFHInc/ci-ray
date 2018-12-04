<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Ray
 *
 * A CI wrapper library to load an instance of Ray.
 *
 * @package Ray
 * @author Colin Rafuse <colin.rafuse@gmail.com>
 */
class Ray extends \TFHInc\Ray\Ray {
    /**
     * Return an instance of Ray
     *
     * @return  Ray
     */
    public function __construct() {
        parent::__construct();
    }
}
