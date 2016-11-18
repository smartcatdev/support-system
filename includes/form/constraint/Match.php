<?php

namespace SmartcatSupport\form\constraint;


class Match implements Constraint {
    private $original;

    public function __construct( $original ) {
        $this->original = $original;
    }

    public function is_valid( $value ) {
        $value == $this->original;
    }
}