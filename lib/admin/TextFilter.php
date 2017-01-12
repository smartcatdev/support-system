<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\TextFilter' ) ) :

class TextFilter implements ValidationFilter {

    public function filter( $value ) {
        return sanitize_text_field( $value );
    }
}

endif;