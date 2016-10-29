<?php

namespace SmartcatSupport\form\constraint;

class Required implements Constraint {
    public function is_valid( $value ) {
        return !empty( $value );
    }
}
