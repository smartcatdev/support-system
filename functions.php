<?php

namespace SmartcatSupport\api;

function convert_html_specialchars( $text ) {
    $matches = [];

    preg_match_all( '#<code>(.*?)</code>#', $text, $matches );

    foreach( $matches[1] as $match ) {
        $text = str_replace( $match, htmlspecialchars( $match ), $text );
    }

    return $text;
}
