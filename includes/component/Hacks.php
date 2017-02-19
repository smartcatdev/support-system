<?php

namespace SmartcatSupport\component;


use smartcat\core\AbstractComponent;

class Hacks extends AbstractComponent {

    /**
     * Hack to remove all comments on support tickets from feeds.
     *
     * @param $for_comments
     * @since 1.0.0
     */
    public function remove_feed_comments( $for_comments ) {
        if( $for_comments ) {

            $comments = $GLOBALS['wp_query']->comments;
            $num_comments = $GLOBALS['wp_query']->comment_count;

            for( $ctr = 0; $ctr < $num_comments; $ctr++ ) {

                $post = get_post( $comments[ $ctr ]->comment_post_ID );

                if ( $post && $post->post_type == 'support_ticket' ) {
                    unset( $GLOBALS['wp_query']->comments[ $ctr ] );
                    $GLOBALS['wp_query']->comment_count--;
                }
            }
        }
    }

    /**
     * Hack to remove all ticket comments from recent comments widget.
     *
     * @param $args
     * @return mixed $args
     * @since 1.0.0
     */
    public function remove_widget_comments( $args ) {
        $args['post__not_in'] = $this->plugin->ticket_ids;

        return $args;
    }

    public function subscribed_hooks() {
        return array(
            'widget_comments_args' => array( 'remove_widget_comments' ),
            'do_feed_rss2' => array( 'remove_feed_comments', 1 ),
            'do_feed_rss' => array( 'remove_feed_comments', 1 ),
            'do_feed_rdf' => array( 'remove_feed_comments', 1 ),
            'do_feed_atom' => array( 'remove_feed_comments', 1 ),
            'do_feed' => array( 'remove_feed_comments', 1 )
        );
    }
}