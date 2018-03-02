<?php
/**
 *
 * @since 1.6.0
 * @package ucare
 */
namespace ucare;


/**
 * Helper class for managing support tickets.
 *
 *
 * @since 1.6.0
 * @package ucare
 */
final class TicketAPI {

    use Singleton;

    /**
     * Initialize API instance.
     *
     * @since 1.6.0
     * @return void
     */
    protected function initialize() {}

    /**
     * Insert a support ticket.
     *
     * @param string|array $args {
     *      Arguments supplied when creating a new support ticket.
     *
     *      @param
     * }
     *
     * @since 1.6.0
     * @return int|\WP_Error
     */
    public function insert_ticket( $args ) {
        $author_id = pluck( $args, 'author', wp_get_current_user()->ID );
        $defaults  = array(
            'id'         => 0,
            'author'     => $author_id,
            'subject'    => '',
            'content'    => '',
        );

        $update = false;

        if ( !empty( $args['id'] ) ) {
            $ticket = get_post( $args['id'] );

            if ( !$ticket || !is_support_ticket( $ticket ) ) {
                return new \WP_Error( 'invalid_ticket' );
            } else {
                $update = true;
            }
        }

        $data = self::validate_ticket( wp_parse_args( $args, $defaults ), $update );

        if ( is_wp_error( $data ) ) {
            return $data;
        }

        $insert = array(
            'ID'            => $data['id'],
            'post_author'   => $data['author'],
            'post_content'  => $data['content'],
            'post_title'    => $data['subject'],
            'post_type'     => 'support_ticket',
            'post_status'   => 'publish',
            'meta_input'    => array(
                'published' => true
            )
        );

        if ( !empty( $data['category'] ) ) {
            $category = array(
                'ticket_category' => array( $data['category'] )
            );
            $insert['tax_input'] = $category;
        }

        if ( !empty( $data['receipt_id'] ) ) {
            $insert['meta_input']['receipt_id'] = sanitize_text_field( $data['receipt_id'] );
        }

        $validated = array(
            'product',
            'agent',
            'status',
            'priority'
        );

        foreach ( $validated as $key ) {
            if ( isset( $data[ $key ] ) ) {
                $insert['meta_input'][ $key ] = $data[ $key ];
            }
        }

        $id = wp_insert_post( $insert );

        if ( !$id || is_wp_error( $id ) ) {
            return $id;
        }

        $ticket = get_post( $id );

        if ( $update ) {
            /**
             *
             * @since 1.6.0
             */
            do_action( 'support_ticket_updated', $ticket, $ticket->ID );
        } else {
            /**
             *
             * @since 1.6.0
             */
            do_action( 'support_ticket_created', $ticket, $ticket->ID );
        }

        return $ticket;
    }

    /**
     * Sanitize ticket data.
     *
     * @param array $args
     * @param bool  $update
     *
     * @since 1.6.0
     * @return array|\WP_Error
     */
    public static function validate_ticket( $args, $update = false ) {
        if ( !empty( $args['status'] ) && !array_key_exists( $args['status'], get_ticket_statuses() ) ) {
            return new \WP_Error( 'invalid_status', sprintf( __( "Ticket status '%s' is invalid", 'ucare' ), $args['status'] ) );
        }

        if ( !empty( $args['priority'] ) && !array_key_exists( $args['priority'], ticket_priorities() ) ) {
            return new \WP_Error( 'invalid_priority', sprintf( __( "Ticket priority '%s' is invalid", 'ucare' ), $args['priority'] ) );
        }

        if ( !empty( $args['agent'] ) && !ucare_is_support_agent( $args['agent'] ) ) {
            return new \WP_Error( 'invalid_agent', sprintf( __( "Agent ID '%d' is invalid", 'ucare' ), $args['agent'] ) );
        }

        if ( !empty( $args['product'] ) && !is_product( $args['product'] ) ) {
            return new \WP_Error( 'invalid_product', sprintf( __( "Product ID '%d' is invalid", 'ucare' ), $args['product'] ) );
        }

        if ( !empty( $args['category'] ) && !self::get_ticket_category( $args['category'] ) ) {
            return new \WP_Error( 'invalid_category', sprintf( __( "Category '%d' is invalid", 'ucare' ), $args['category'] ) );
        }

        if ( !$update && !empty( $args['author'] ) && ucare_is_support_user( $args['author'] ) ) {
            return new \WP_Error( 'invalid_author', sprintf( __( "Author ID'%d' is invalid", 'ucare' ), $args['author'] ) );
        }

        if ( !$update && empty( $args['subject'] ) ) {
            return new \WP_Error( 'invalid_subject', __( 'Ticket subject cannot be empty', 'ucare' ) );
        }

        if ( !$update && empty( $args['content'] ) ) {
            return new \WP_Error( 'invalid_content', __( 'Ticket content cannot be empty', 'ucare' ) );
        }

        return $args;
    }

    /**
     * Search for a ticket category.
     *
     * @since 1.6.0
     * @return bool
     */
    private static function get_ticket_category( $id_or_slug ) {
        global $wpdb;

        $sql = "SELECT term_id, slug 
                FROM $wpdb->terms
                INNER JOIN $wpdb->term_taxonomy
                  USING (term_id)
                 WHERE taxonomy = 'ticket_category'
                  AND term_id = %s OR slug = %s";

        $parsed = $wpdb->prepare( $sql, array( $id_or_slug, $id_or_slug ) );
        $result = $wpdb->get_row( $parsed );

        return !empty( $result );
    }
}


add_action('init', function () {
    var_dump(ucare_ticket_api()->insert_ticket(array(
        'id' => 26,
        'category' => 'sad',
        'status' => 'waiting',
        'content' => 'sdfsdfdsf'
    ))) ; die;
}, 100 );