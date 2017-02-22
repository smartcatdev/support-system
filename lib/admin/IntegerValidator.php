<?php

namespace smartcat\admin;


class IntegerValidator implements ValidationFilter {
    public function filter( $value ) {
        return absint( $value );
    }
}
