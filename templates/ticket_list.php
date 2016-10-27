<?php 

/**
 * headings
 * data
 * footer
 */

use const SmartcatSupport\TEXT_DOMAIN; ?>

<table>
    <tr>
        <th>
            stuff
        </th>
    </tr>

        <?php while( $wp_query->have_posts() ) : $wp_query->the_post() ?>
    <tr>
            <td>
                <?php echo the_title(); ?>
            </td>
    </tr>
        <?php endwhile; ?>

</table>
