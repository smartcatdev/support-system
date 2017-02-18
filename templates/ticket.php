<?php

use smartcat\form\Form;
use SmartcatSupport\descriptor\Option;
use SmartcatSupport\Plugin;
use SmartcatSupport\util\TicketUtils;

ob_start();

?>

<div class="row">

    <div class="sidebar col-sm-4"></div>

    <div class="col-sm-8">

        <div class="ticket panel panel-default ">

            <div class="panel-heading">

                <p class="panel-title"><?php esc_html_e( $ticket->post_title ); ?></p>

            </div>

            <div class="panel-body">

                <p><?php echo $ticket->post_content; ?></p>

            </div>

        </div>

    </div>

    <div class="comments col-sm-8"></div>


    <div class="col-sm-8 comment-reply-wrapper">

        <div class="comment-reply panel panel-default ">

            <div class="panel-heading">

                <div class="media meta">

                    <div class="media-left">

                        <?php echo get_avatar( $ticket, 28, '', '', array( 'class' => 'img-circle media-object' ) ); ?>

                    </div>

                </div>

            </div>

            <div class="panel-body">

                <div class="editor">

                    <form class="comment-form">

                        <textarea class="editor-content" name="content" rows="5"></textarea>

                        <input type="hidden" name="id" value="<?php echo $ticket->ID; ?>">

                        <?php wp_comment_form_unfiltered_html_nonce(); ?>

                        <div class="row">

                            <div class="bottom col-sm-12">

                                <button type="submit" class="button button-submit" disabled="true">

                                    <?php _e( get_option( Option::REPLY_BTN_TEXT, Option\Defaults::REPLY_BTN_TEXT ) ); ?>

                                </button>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php return ob_get_clean(); ?>
