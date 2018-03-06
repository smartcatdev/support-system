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
final class API {

    use Singleton;

    /**
     * Initialize API instance.
     *
     * @since 1.6.0
     * @return void
     */
    protected function initialize() {}

    /**
     * Insert or updates a support ticket.
     *
     * @param string|array $args {
     *  Arguments supplied when creating a new support ticket.
     *
     *  @param int        $id
     *  @param int        $author
     *  @param string     $subject
     *  @param string     $body
     *  @param int        $agent
     *  @param string     $status
     *  @param int        $priority
     *  @param int        $product
     *  @param int|string $category
     * }
     *
     * @since 1.6.0
     * @return \WP_Post|\WP_Error
     */
    public function insert_ticket( $args ) {
        $author_id = pluck( $args, 'author', wp_get_current_user()->ID );
        $defaults = array(
            'id'         => 0,
            'author'     => $author_id,
            'subject'    => '',
            'body'       => '',
            'agent'      => '',
            'status'     => 'new',
            'priority'   => 0,
            'product'    => '',
            'category'   => ''
        );
        $update = false;
        $ticket = get_post( pluck( $args, 'id', 0 ) );

        if ( $ticket && is_support_ticket( $ticket ) ) {
            $update = true;
        }

        $data = self::sanitize_ticket( wp_parse_args( $args ), $defaults, $update );

        if ( is_wp_error( $data ) ) {
            return $data;
        }

        $insert = array(
            'ID'         => $update ? $ticket->ID : 0,
            'post_type'  => 'support_ticket',
            'meta_input' => array()
        );

        $post_fields = array(
            'author'  => 'post_author',
            'subject' => 'post_title',
            'body'    => 'post_content'
        );
        foreach ( $post_fields as $key => $field ) {
            if ( !empty( $data[ $key ] ) ) {
                $insert[ $field ] = $data[ $key ];
            }
        }

        if ( !$update ) {
            $insert['post_status'] = 'publish';
        }

        if ( !empty( $data['category'] ) ) {
            $category = array(
                'ticket_category' => (array) $data['category']
            );
            $insert['tax_input'] = $category;
        }

        if ( !empty( $args['receipt_id'] ) ) {
            $insert['meta_input']['receipt_id'] = sanitize_text_field( $args['receipt_id'] );
        }

        $meta_keys = array(
            'product',
            'agent',
            'status',
            'priority'
        );
        foreach ( $meta_keys as $key ) {
            if ( isset( $data[ $key ] ) ) {
                $insert['meta_input'][ $key ] = $data[ $key ];
            }
        }

        $id = $update ? wp_update_post( $insert ) : wp_insert_post( $insert );

        if ( !$id || is_wp_error( $id ) ) {
            return $id;
        }

        $ticket = get_post( $id );

        if ( $ticket->post_status !== 'publish' ) {
            return $ticket; // Skip unpublished ticket notifications
        }

        if ( get_post_meta( 'published', $id, true ) ) {
            /**
             *
             * @since 1.6.0
             */
            do_action( 'support_ticket_updated', $ticket, $ticket->ID );
        } else {
            update_post_meta( $id, 'published', true );
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
     * @param array $defaults
     * @param bool  $update
     *
     * @since 1.6.0
     * @return array|\WP_Error
     */
    private static function sanitize_ticket( $args, $defaults, $update = false ) {
        $data = array();

        foreach ( $defaults as $key => $default ) {
            if ( !isset( $args[ $key ] ) ) {
                if ( $update ) {
                    continue; // Skip validating empty update args
                } else {
                    $args[ $key ] = $default;
                }
            }

            switch ( $key ) {
                case 'status':
                    if ( !array_key_exists( $args['status'], get_ticket_statuses() ) ) {
                        return new \WP_Error( 'invalid_status', sprintf( __( "Ticket status '%s' is invalid", 'ucare' ), $args['status' ] ) );
                    }

                    break;

                case 'priority':
                    if ( !array_key_exists( $args['priority'], ticket_priorities() ) ) {
                        return new \WP_Error( 'invalid_priority', sprintf( __( "Ticket priority '%s' is invalid", 'ucare' ), $args['priority'] ) );
                    }

                    break;

                case 'agent':
                    if ( !empty( $args['agent'] ) && !ucare_is_support_agent( $args['agent'] ) ) {
                        return new \WP_Error( 'invalid_agent', sprintf( __( "Agent ID '%d' is invalid", 'ucare' ), $args['agent'] ) );
                    }

                    break;

                case 'product':
                    if ( !empty( $args['product'] ) && !is_product( $args['product'] ) ) {
                        return new \WP_Error( 'invalid_product', sprintf( __( "Product ID '%d' is invalid", 'ucare' ), $args['product'] ) );
                    }

                    break;

                case 'category':
                    if ( !empty( $args['category'] ) ) {
                        $category = self::get_ticket_category( $args['category'] );
                        if ( empty( $category ) ) {
                            return new \WP_Error( 'invalid_category', sprintf( __( "Category '%s' is invalid", 'ucare' ), $args['category'] ) );
                        }
                    }

                    break;

                case 'author':
                    if ( !ucare_is_support_user( $args['author'] ) ) {
                        return new \WP_Error( 'invalid_author', sprintf( __( "Author ID'%d' is invalid", 'ucare' ), $args['author'] ) );
                    }

                    break;

                case 'subject':
                    if ( empty( $args['subject'] ) ) {
                        return new \WP_Error( 'invalid_subject', __( 'Ticket subject cannot be empty', 'ucare' ) );
                    }

                    break;

                case 'body':
                    if ( empty( $args['body'] ) ) {
                        return new \WP_Error( 'invalid_content', __( 'Ticket content cannot be empty', 'ucare' ) );
                    }

                    break;
            }

            $data[ $key ] = $args[ $key ];
        }

        return $data;
    }

    /**
     * Search for a ticket category.
     *
     * @since 1.6.0
     * @return bool
     */
    private static function get_ticket_category( $id_or_slug ) {
        global $wpdb;

        $sql = "SELECT slug 
                FROM $wpdb->terms
                INNER JOIN $wpdb->term_taxonomy
                  USING (term_id)
                 WHERE taxonomy = 'ticket_category'
                  AND term_id = %s OR slug = %s";

        $parsed = $wpdb->prepare( $sql, array( $id_or_slug, $id_or_slug ) );
        return $wpdb->get_var( $parsed );
    }
}
