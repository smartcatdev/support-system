<?php ob_start(); ?>

    <div>

        <h2>Reason</h2>
        <p style="margin-left: 20px"><?php echo $_POST['reason']; ?></p>

        <?php if( !empty( $_POST['details'] ) ) : ?>

            <h2>Details</h2>
            <p style="margin-left: 20px"><?php echo $_POST['details']; ?></p>

        <?php endif; ?>

        <?php if( !empty( $_POST['comments'] ) ) : ?>

            <h2>Comments</h2>
            <p style="margin-left: 20px"><?php echo $_POST['comments']; ?></p>

        <?php endif; ?>

        <a href="<?php echo home_url(); ?>">website can be viewed here</a>

    </div>

<?php return ob_get_clean(); ?>