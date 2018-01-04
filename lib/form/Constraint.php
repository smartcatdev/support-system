<?php

namespace smartcat\form;

if( !interface_exists( '\smartcat\form\Constraint' ) ) :
    /**
     * @deprecated
     */
interface Constraint {
    public function is_valid( $value );
}

endif;