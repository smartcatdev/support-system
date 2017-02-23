<?php ?>

<div class="ticket-list col-sm-12">

    <div class="list-group">

        <?php foreach( $query->posts as $post ) : ?>

            <div class="list-group-item ticket">

                <div class="media">

                <div class="media-body">

                    <a class="open-ticket" href="#" data-id="<?php echo $post->ID; ?>"><h4 class="ticket-title"><?php echo $post->post_title; ?></h4></a>
                    <p class="text-muted"># <?php echo $post->ID; ?> opened by <?php echo get_the_author_meta( 'display_name', $post->post_author ); ?></p>

                </div>

                <div class="media-right">

                    <span class="glyphicon glyphicon-flag pull-right"></span>

                </div>
                </div>
            </div>

        <?php endforeach; ?>

        <div class="bottom text-right">
            <ul class="pagination">

                <?php for( $ctr = 0; $ctr < $query->max_num_pages; $ctr++ ): ?>

                    <?php $page = $ctr + 1; ?>

                    <li class="<?php echo ( $query->query['paged'] == $page ? 'active' : '' ); ?>">

                        <a class="page" href="#" data-id="<?php echo $page; ?>"><?php echo $page; ?></a>

                    </li>

                <?php endfor; ?>

            </ul>
        </div>

    </div>

</div>