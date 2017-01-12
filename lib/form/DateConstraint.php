<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\DateConstraint' ) ) :

class DateConstraint implements Constraint {
    public function is_valid( $value ) {
        return date_create( $value ) !== false;
    }
}

endif;