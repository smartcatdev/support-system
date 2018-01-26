<?php

namespace ucare;

// Register the Ticket Category taxonomy
add_action( 'init', 'ucare\register_category_taxonomy' );

// add category color to add category form
add_action( 'ticket_category_add_form_fields', 'ucare\add_ticket_category_color' );

// add category color to the edit category form
add_action( 'ticket_category_edit_form_fields', 'ucare\edit_ticket_category_color' );

// Handle saving the category color
add_action( 'edit_ticket_category',   'ucare\category_color_save_term_fields' );
add_action( 'edited_ticket_category', 'ucare\category_color_save_term_fields' );
add_action( 'create_ticket_category', 'ucare\category_color_save_term_fields' );

// Insert category color column
add_filter( 'manage_edit-ticket_category_columns', 'ucare\category_color_add_terms_table_columns' );

// Render the category color data
add_filter( 'manage_ticket_category_custom_column', 'ucare\category_color_render_terms_table_columns', 10, 3 );


function register_category_taxonomy() {

    $labels = array(
        'name'                       => _x( 'Ticket Categories', 'taxonomy general name', 'ucare' ),
        'singular_name'              => _x( 'Ticket Category', 'taxonomy singular name', 'ucare' ),
        'all_items'                  => __( 'All Categories', 'ucare' ),
        'edit_item'                  => __( 'Edit Category', 'ucare' ),
        'view_item'                  => __( 'View Category', 'ucare' ),
        'update_item'                => __( 'Update Category', 'ucare' ),
        'add_new_item'               => __( 'Add New Category', 'ucare' ),
        'new_item_name'              => __( 'New Category', 'ucare' ),
        'parent_item'                => __( 'Parent Category', 'ucare' ),
        'parent_item_colon'          => __( 'Parent Category:', 'ucare' ),
        'search_items'               => __( 'Search Categories', 'ucare' ),
        'popular_items'              => __( 'Popular Categories', 'ucare' ),
        'not_found'                  => __( 'No categories found', 'ucare' ),
        'add_or_remove_items'        => __( 'Add or remove categories', 'ucare' ),
        'choose_from_most_used'      => __( 'Choose from the most used categories', 'ucare' ),
        'separate_items_with_commas' => __( 'Separate categories with commas', 'ucare' )
    );

    $args = array(
        'label'        => __( 'Categories', 'ucare' ),
        'labels'       => $labels,
        'hierarchical' => false
    );

    register_taxonomy( 'ticket_category', 'support_ticket', $args );

}

/**
 * 
 * @since 1.5.1
 */
function add_ticket_category_color() { ?>

    <div class="form-field term-color">
        <label for="tag-slug">
            <?php _e( 'Category color', 'ucare' ); ?>
        </label>
        <?php wp_nonce_field( 'save_category_color', 'category_color_nonce' ); ?>
        <input name="category_color" id="ucare-category-color" type="text">
    </div>

<?php }

/**
 * 
 * @param WP_Term $term
 */
function edit_ticket_category_color( $term ) { ?>

    <tr class="form-field form-required term-color-wrap">
        <th scope="row"><label for="color"><?php _e( 'Color', 'ucare' ); ?></label></th>
        <td>
            <?php wp_nonce_field( 'save_category_color', 'category_color_nonce' ); ?>
            <input name="category_color" 
                   id="ucare-category-color" 
                   type="text" 
                   value="<?php echo get_term_meta( $term->term_id, 'category_color', true ); ?>" 
                   aria-required="true">
            
            <p class="description">
                <?php _e( 'Select the color you want to associate with this category', 'ucare' ) ?>
            </p>
        </td>
    </tr>

<?php }

/**
 * 
 * Handle saving category color term meta
 * 
 * @param int $term_id
 * @since 1.5.1
 */
function category_color_save_term_fields( $term_id ) {

    if ( \ucare\verify_request_nonce( 'save_category_color', 'category_color_nonce' ) ) {

        $old = get_term_meta( $term_id, 'category_color', true );
        $new = \ucare\get_var( 'category_color' );

        if ( empty( $new ) ) {
            delete_term_meta( $term_id, 'category_color' );
        } else if ( $new !== $old ) {
            update_term_meta( $term_id, 'category_color', $new );
        }

    }

}

/**
 * 
 * 
 * @param $columns
 * @return array
 * @since 1.5.1
 */
function category_color_add_terms_table_columns( $columns ) {
    
    $custom = array(
        'category_color' => __( 'Color', 'ucare' ),
    );

    return array_merge( $columns, $custom );

}

/**
 * 
 * 
 * @param $output
 * @param $column
 * @param $term_id
 * @return string
 * @since 1.5.1
 *
 */
function category_color_render_terms_table_columns( $output, $column, $term_id ) {
    
    if( $column != 'category_color' ) {
        return;
    }
    
    $color = get_term_meta( $term_id, 'category_color', true );
    
    $out = '<span class="ucare-category-color" style="background-color:' . esc_attr( $color ) . '"></span>';
    
    return $out;
}