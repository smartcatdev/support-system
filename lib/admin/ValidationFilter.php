<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\ValidationFilter' ) ) :

interface ValidationFilter {
    public function filter( $value );
}

endif;