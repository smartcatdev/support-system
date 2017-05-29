<?php

namespace smartcat\admin;


class RangeValidator implements ValidationFilter {

    protected $min;
    protected $max;
    protected $default;

    public function __construct( $min, $max, $default ) {
        $this->min = $min;
        $this->max = $max;
        $this->default = $default;
    }

    public function filter( $value ) {
        if( $value < $this->min || $value > $this->max ) {
            $value = $this->default;
        }

        return $value;
    }
}