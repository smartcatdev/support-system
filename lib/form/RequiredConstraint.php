<?php

namespace smartcat\form;

if( !class_exists( '\smartcat\form\RequiredConstraint' ) ) :

class RequiredConstraint implements Constraint {
    public function is_valid( $value ) {
        return !empty( $value );
    }
}

endif;