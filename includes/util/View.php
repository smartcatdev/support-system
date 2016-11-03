<?php

namespace SmartcatSupport\util;

/**
 * Renders and returns the output of a PHP template file.
 *
 * @since 1.0.0
 * @package template
 * @author Eric Green <eric@smartcat.ca>
 */
class View {
    private $template_dir;
    
    /**
     * @param string $dir The path to the template file.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function __construct( $dir ) {
        $this->set_template_dir( $dir );
    }
 
    /**
     * Render the template and capture its output.
     *
     * @param string $template The template to render.
     * @param array $data (Default NULL) Any data required to be output in the template.
     * @return string The rendered HTML.
     * @since 1.0.0
     * @author Eric Green <eric@smartcat.ca>
     */
    public function render( $template, array $data = null ) {
        if( !is_null( $data ) ) {
            extract( $data );
        }
        
        ob_start();
            
        include ( $this->template_dir . '/' . $template . '.php' );
            
        return ob_get_clean();
    }
    
    public function set_template_dir( $dir ) {
        $this->template_dir = $dir;
    }
}
