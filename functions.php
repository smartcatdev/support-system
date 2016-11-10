<?php

namespace SmartcatSupport\api;

function filter_code_from_text( $text ) {
    $matches = [];

    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );

    foreach( $matches[1] as $match ) {
        $text = str_replace( $match, htmlspecialchars( $match ), $text );
    }

    return $text;
}
