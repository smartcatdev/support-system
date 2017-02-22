<?php ?>



<div class="list-group">

    <?php foreach( $query->posts as $post ) : ?>

        <div class="list-group-item">

            <a class="open-ticket" href="#" data-id="<?php echo $post->ID; ?>"># <?php echo $post->ID; ?> <?php echo $post->post_title; ?></a>

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
