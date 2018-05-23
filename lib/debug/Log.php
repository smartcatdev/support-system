<?php

namespace smartcat\debug;

if( !class_exists( '\smartcat\debug\Log' ) ) :
    /**
     * Class Log
     * @deprecated
     * @package smartcat\debug
     */
    class Log {
        public static function dump( $object ) {
            ob_start();
            var_dump( $object );
            error_log( ob_get_clean() );
        }
    }

endif;