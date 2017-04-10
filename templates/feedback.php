<?php

?>

<div id="deactivate-feedback" class="support-admin-modal">

    <div class="modal-wrap">

        <form class="feedback-form" action="" method="post">

            <div class="modal-header">

                <h3><?php _e( 'If you have a moment, please let us know why you are deactivating' ); ?></h3>

            </div>

            <div class="modal-content">

                <h3><?php _e( 'Reason for deactivating', \SmartcatSupport\PLUGIN_ID ); ?></h3>

                <ul>
                    <li>
                        <label>
                            <input type="radio" name="reason" value="I don't need this plugin"><?php _e( 'I don\'t need this plugin', \SmartcatSupport\PLUGIN_ID ); ?>
                        </label>
                    </li>
                    <li data-type="textarea" data-placeholder="<?php _e( 'What features would you like to see?', \SmartcatSupport\PLUGIN_ID ); ?>">
                        <label>
                            <input type="radio" name="reason" value="This plugin lacks the features I need"><?php _e( 'This plugin lacks the features I need', \SmartcatSupport\PLUGIN_ID ); ?>
                        </label>
                    </li>
                    <li data-type="textarea" data-placeholder="<?php _e( 'What did you expect?', \SmartcatSupport\PLUGIN_ID ); ?>">
                        <label>
                            <input type="radio" name="reason" value="The plugin does not function as expected"><?php _e( 'The plugin does not function as expected', \SmartcatSupport\PLUGIN_ID ); ?>
                        </label>
                    </li>
                    <li data-type="textarea" data-placeholder="<?php _e( 'Can you explain what is happening?', \SmartcatSupport\PLUGIN_ID ); ?>">
                        <label>
                            <input type="radio" name="reason" value="The plugin does not work"><?php _e( 'The plugin does not work', \SmartcatSupport\PLUGIN_ID ); ?>
                        </label>
                    </li>
                    <li data-type="text" data-placeholder="<?php _e( 'Which one?', \SmartcatSupport\PLUGIN_ID ); ?>">
                        <label>
                            <input type="radio" name="reason" value="I found a better plugin"><?php _e( 'I found a better plugin', \SmartcatSupport\PLUGIN_ID ); ?>
                        </label>
                    </li>
                    <li>
                        <label>
                            <input type="radio" name="reason" value="Other"><?php _e( 'Other', \SmartcatSupport\PLUGIN_ID ); ?>
                        </label>
                    </li>
                </ul>

                <h3><?php _e( 'Additional comments', \SmartcatSupport\PLUGIN_ID ); ?></h3>

                <textarea name="comments" maxlength="250" rows="5"></textarea>

                <input type="hidden" name="product_feedback">

            </div>

            <div class="modal-footer">

                <a class="deactivate-url" href="#"><?php _e( 'No thanks, just deactivate', \SmartcatSupport\PLUGIN_ID ); ?></a>

                <button id="close-feedback" class="button-secondary"><?php _e( 'Cancel', SmartcatSupport\PLUGIN_ID ); ?></button>
                <button type="submit" class="button-primary"><?php _e( 'Submit & Deactivate', SmartcatSupport\PLUGIN_ID ); ?></button>


            </div>

        </form>

    </div>

</div>
