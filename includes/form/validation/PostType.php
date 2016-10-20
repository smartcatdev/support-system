<?php

namespace SmartcatSupport\form\validation;

/**
 * Description of PostType
 *
 * @author Eric Green <eric@smartcat.ca>
 */
class PostType extends Constraint {
    protected $post_type;
    
    public function __construct( $message = '', $post_type ) {
        parent::__construct( $message );
        $this->post_type = $post_type;
    }
    
    public function is_valid( $value ) {
        return get_post( $value )->post_type == $this->type;
    }
}
