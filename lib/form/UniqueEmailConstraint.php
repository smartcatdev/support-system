<?php

namespace smartcat\form;

/**
 * @deprecated
 */
class UniqueEmailConstraint implements Constraint {

    public function is_valid( $value ) {
        return !email_exists( $value ) && !username_exists( $value );
    }
}
