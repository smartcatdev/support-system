<?php

namespace smartcat\form;

if( !interface_exists( '\smartcat\form\Constraint' ) ) :

interface Constraint {
    public function is_valid( $value );
}

endif;