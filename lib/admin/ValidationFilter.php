<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\ValidationFilter' ) ) :
    /**
     * Interface ValidationFilter
     * @deprecated
     * @package smartcat\admin
     */
interface ValidationFilter {
    public function filter( $value );
}

endif;