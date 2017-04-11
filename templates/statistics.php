
<div class="col-sm-2 stat-tab">
    <h4><?php _e( 'Total tickets', \SmartcatSupport\PLUGIN_ID ); ?></h4>
    <?php echo \SmartcatSupport\statprocs\get_unclosed_tickets(); ?>
</div>

<div class="col-sm-2 stat-tab">
    <h4><?php _e( 'Needs attention', \SmartcatSupport\PLUGIN_ID ); ?></h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'needs_attention'
    ) ) ?>
</div>

<div class="col-sm-2 stat-tab">
    <h4><?php _e( 'Awaiting response', \SmartcatSupport\PLUGIN_ID ); ?></h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'responded'
    ) ) ?>
</div>

<div class="col-sm-2 stat-tab">
    <h4><?php _e( 'Waiting', \SmartcatSupport\PLUGIN_ID ); ?></h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'status'    => 'waiting'
    ) ) ?>
</div>

<div class="col-sm-2 stat-tab">
    <h4><?php _e( 'Assigned to me', \SmartcatSupport\PLUGIN_ID ); ?></h4>
    <?php echo \SmartcatSupport\statprocs\get_ticket_count( array(
        'agent'    => get_current_user_id()
    ) ) ?>
</div>

