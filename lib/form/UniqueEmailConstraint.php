<?php

namespace smartcat\form;


class UniqueEmailConstraint implements Constraint {

    public function is_valid( $value ) {
        return !email_exists( $value ) && !username_exists( $value );
    }
}
