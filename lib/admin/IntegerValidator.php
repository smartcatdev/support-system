<?php

namespace smartcat\admin;

/**
 * Class IntegerValidator
 * @deprecated
 * @package smartcat\admin
 */
class IntegerValidator implements ValidationFilter {
    public function filter( $value ) {
        return absint( $value );
    }
}
