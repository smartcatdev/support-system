<?php

namespace ucare\component;

use smartcat\core\AbstractComponent;

class Hacks extends AbstractComponent {

    private $wpdb;

    public function start() {
        $this->wpdb = $GLOBALS['wpdb'];
    }

    public function remove_feed_comments( $where ) {
        return $where . " AND {$this->wpdb->posts}.post_type NOT IN ( 'support_ticket' )";
    }

    /**
     * Hack to remove all ticket comments from recent comments widget.
     *
     * @param $args
     * @return mixed $args
     * @since 1.0.0
     */
    public function remove_widget_comments( $args ) {
        $args['post_type'] = array( 'post', 'page' );

        return $args;
    }

    public function remove_admin_comments( $query ) {

    	if ( is_admin() && isset( get_current_screen()->id ) && get_current_screen()->id == 'dashboard'  ) {

		    $query['join']  .= "INNER JOIN {$this->wpdb->posts} ON {$this->wpdb->posts}.ID = {$this->wpdb->comments}.comment_post_ID";
		    $query['where'] .=  " AND {$this->wpdb->posts}.post_type NOT IN ( 'support_ticket' )";

        }

        return $query;
    }

    public function subscribed_hooks() {
        return array(
            'widget_comments_args' => array( 'remove_widget_comments' ),
            'comment_feed_where' => array( 'remove_feed_comments' ),
            'comments_clauses' => array( 'remove_admin_comments' )
        );
    }
}