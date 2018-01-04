<?php

namespace smartcat\form;

/**
 * @deprecated
 */
class MatchConstraint implements Constraint {
    private $match;

    public function __construct( $match ) {
        $this->match = $match;
    }

    public function is_valid( $value ) {
        return $value == $this->match;
    }
}