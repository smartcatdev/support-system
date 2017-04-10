<?php

namespace smartcat\admin;


class HTMLFilter implements ValidationFilter {

    public function filter( $value ) {
        return wp_filter_post_kses( $value );
    }
}