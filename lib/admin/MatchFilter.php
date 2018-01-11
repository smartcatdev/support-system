<?php

namespace smartcat\admin;

if( !class_exists( '\smartcat\admin\MatchFilter' ) ) :
    /**
     * Class MatchFilter
     * @deprecated
     * @package smartcat\admin
     */
class MatchFilter implements ValidationFilter {

    protected $valid_values;
    protected $fallback;

    public function __construct( array $valid_values, $fallback ) {
        $this->valid_values = $valid_values;
        $this->fallback = $fallback;
    }


    public function filter( $value ) {
        if( !in_array( $value, $this->valid_values ) ) {
            $value = $this->fallback;
        }

        return $value;
    }
}

endif;