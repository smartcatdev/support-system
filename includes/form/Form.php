<?php

namespace SmartcatSupport\form;

use SmartcatSupport\action\ActionListener;
use const SmartcatSupport\TEXT_DOMAIN;

class Form extends ActionListener {
    protected $id;
    protected $fields = [];
    
    public function __construct( $id ) {
        $this->id = $id;
    }
    
    public function save() {
        $values = false;
        
        // Verify the form's nonce
        if( isset( $_POST[ $this->nonce() ] ) && wp_verify_nonce( $_POST[ $this->nonce() ], 'save' ) ) {
            $values = [];
            
            // Call each of the field's validate methods
            foreach( $this->fields as $id => $field ) {
                if( isset( $_POST[ $field->get_id() ] ) ) {
                    $values[ $field->get_id() ] = apply_filters( "save_field_" . $field->get_id(), $_POST[ $field->get_id() ] );
                }
            }
        }
        
        return $values;
    }
    
    public function render( $async = true, $as_section = false ) { 
        if( !$as_section ) : ?> 

            <form id="<?php esc_attr_e( $this->id ) ?>" 
                action="<?php esc_attr_e( $async ? admin_url( 'admin-ajax.php' ) : '' ) ?>"
                method="POST"> 
                
        <?php endif; ?>

        <table class="form-table">
            
            <?php foreach( $this->fields as $id => $field ) : ?>
            
                <tr>
                    <th>
                        <label>
                            <?php esc_html_e( __( $field->get_title(), TEXT_DOMAIN ) ) ?>
                        </label>
                    </th>
                    <td>
                        <?php do_action( "render_field_" . $field->get_id() ) ?>
                        <?php if( $field->get_desc() != '' ) : ?>
                        
                            <p class="description">
                                <?php esc_html_e( $field->get_desc() ) ?>
                            </p>
                        
                        <?php endif;?>
                    </td>
                </tr>
            
            <?php endforeach; ?>
                
        </table>

        <?php wp_nonce_field( 'save', $this->nonce() ); 
        
        if( !$as_section ) : ?> 
                
            </form>   

        <?php endif;
    }
    
    public function add_field( $id, Field $field ) {
        if( !array_key_exists( $id, $this->fields ) ) {
            $field->add_action( "render_field_" . $field->get_id(), 'render' );
            $field->add_action( "save_field_" . $field->get_id(), 'validate' );
                    
            $this->fields[ $id ] = $field;
        }
    }
    
    private function nonce() {
        return $this->id . '_nonce';
    }
}