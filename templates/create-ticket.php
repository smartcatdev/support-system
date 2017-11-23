<?php
/**
 * Page template for creating new support tickets.
 *
 * @since 1.5.0
 * @package ucare
 */
namespace ucare;

?>

<?php get_header(); ?>

    <div class="container">

        <div class="row">

            <form id="create-ticket-form" data-id="<?php echo absint( get_user_draft_ticket() ); ?>">

                <div class="input-group">

                    <span class="input-group-addon" id="basic-addon1">

                    </span>

                    <input type="text" class="form-control" placeholder="<?php _e( 'Receipt #', 'ucare' ); ?>">

                </div>

                <div class="input-group">

                    <span class="input-group-addon" id="basic-addon1">

                    </span>

                    <input type="text" class="form-control" name="title" placeholder="<?php _e( 'Subject', 'ucare' ); ?>">

                </div>

                <div class="form-group">

                    <textarea class="form-control" name="content" placeholder="<?php _e( 'Description', 'ucare' ); ?>"></textarea>

                </div>

                <div class="form-group text-right">
                    <button class="button button-default"><?php _e( 'Create Ticket', 'ucare' ); ?></button>
                </div>

                <!-- User draft ticket -->
                <input type="hidden" name="status" value="publish">

            </form>

            <form id="dropzone">


                <!-- User draft ticket ID -->
                <input type="hidden" name="id" value="<?php echo absint( get_user_draft_ticket() ); ?>">

            </form>

        </div>

    </div>

<?php get_footer(); ?>
