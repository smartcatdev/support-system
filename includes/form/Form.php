<?php

namespace SmartcatSupport\form;

use SmartcatSupport\ActionListener;
use const SmartcatSupport\TEXT_DOMAIN;

/**
 * Smartcat-Form on steroids 
 */
class Form extends ActionListener {
    protected $id;
    protected $fields = [];
    
    public function __construct( $id ) {
        $this->id = $id;
    }
    
    public function validate() {
        $values = false;
        
        // Verify the form's nonce
        if( isset( $_POST[ $this->nonce() ] ) && wp_verify_nonce( $_POST[ $this->nonce() ], 'submit' ) ) {
            $values = [];
            
            // Call each field's validate methods if passed in request
            foreach( $this->fields as $id => $field ) {
                if( isset( $_POST[ $field->get_id() ] ) ) {
                    $values[ $field->get_id() ] = apply_filters( 'validate_field_' . $field->get_id(), $_POST[ $field->get_id() ] );
                }
            }
        }
        
        return $values;
    }
    
    public function render( $async = true, $as_section = false ) { 
        if( !$as_section ) : ?> 

            <form id="<?php esc_attr_e( $this->id ); ?>" 
                action="<?php esc_attr_e( $async ? admin_url( 'admin-ajax.php' ) : '?' ); ?>"
                method="POST"> 
                
        <?php endif; ?>

        <table class="form-table">
            
            <?php foreach( $this->fields as $id => $field ) : ?>
            
                <tr>
                    <th>
                        <label>
                            <?php esc_html_e( __( $field->get_title(), TEXT_DOMAIN ) ); ?>
                        </label>
                    </th>
                    <td>
                        <?php $field->render(); ?>
                        
                        <?php if( $field->get_desc() != '' ) : ?>
                        
                            <p class="description">
                                <?php esc_html_e( $field->get_desc() ); ?>
                            </p>
                        
                        <?php endif; ?>
                    </td>
                </tr>
            
            <?php endforeach; ?>
                
        </table>

        <?php wp_nonce_field( 'submit', $this->nonce() ); 
        
        if( !$as_section ) : ?> 
                
            </form>   

        <?php endif;
    }
    
    public function add_field( $id, Field $field ) {
        if( !array_key_exists( $id, $this->fields ) ) {
            $field->add_action( 'validate_field_' . $field->get_id(), 'validate' );
                    
            $this->fields[ $id ] = $field;
        }
        
        return $this;
    }
    
    public function get_field( $id ) {
        $field = false;
        
        if( array_key_exists( $id, $this->fields ) ) {
            $field = $this->fields[ $id ];
        }
        
        return $field;
    }
    
    public function get_fields() {
        return $this->fields;
    }

    private function nonce() {
        return $this->id . '_nonce';
    }
}
