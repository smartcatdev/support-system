<?php

namespace SmartcatSupport\template;
/**
 * Description of Template
 *
 * @author ericg
 */
class View {
    private $template;
    
    public function __construct( $template ) {
        $this->template = $template;
    }
 
    public function render( $data = null ) {
        $buffer = true;
        
        if( !is_null( $data ) ) {
            extract( $data );
        }
        
        ob_start();
            
        include ( $this->template );
            
        return ob_get_clean();
    }
}
