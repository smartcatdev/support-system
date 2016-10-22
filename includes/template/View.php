<?php

namespace SmartcatSupport\template;

/**
 * Renders and returns the output of a PHP template file.
 *
 * @since 1.0.0
 * @package template
 * @author Eric Green <eric@smartcat.ca>
 */
class View {
    private $template;
    
    /**
     * @param string $template The path to the template file.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( $template ) {
        $this->template = $template;
    }
 
    /**
     * Render the template and capture its output.
     * 
     * @param mixed $data (Default NULL) Any data required to be output in the template.
     * @return string The rendered HTML.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function render( $data = null ) {
        if( is_array( $data ) ) {
            extract( $data );
        }
        
        ob_start();
            
        include ( $this->template );
            
        return ob_get_clean();
    }
}
