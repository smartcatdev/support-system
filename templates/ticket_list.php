<?php ?>



<div class="list-group ticket-list">

    <?php foreach( $query->posts as $post ) : ?>

        <div class="list-group-item ticket">

            <a class="open-ticket" href="#" data-id="<?php echo $post->ID; ?>"><h4 class="ticket-title"><?php echo $post->post_title; ?></h4></a>

            <p class="muted"># <?php echo $post->ID; ?> opened by <?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></p>

        </div>

    <?php endforeach; ?>

    <div class="bottom text-right">
        <ul class="pagination">

            <?php for( $ctr = 0; $ctr < $query->max_num_pages; $ctr++ ): ?>

                <li><a class="page" href="#" data-id="<?php echo $ctr + 1; ?>"><?php echo $ctr + 1; ?></a></li>

            <?php endfor; ?>

        </ul>
    </div>

</div>
