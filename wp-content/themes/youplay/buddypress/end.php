<?php
/**
 * End of BuddyPress main pages
 *
 * @package Youplay
 */

$side = strpos(yp_opts('buddypress_layout', true), 'side-cont') !== false
                    ? 'left'
                    : (strpos(yp_opts('buddypress_layout', true), 'cont-side') !== false
                      ? 'right'
                      : false);
?>

        </main>

        <?php
            // check if right sidebar
            if ($side == 'right') {
                get_sidebar( 'buddypress' );
            }
        ?>
    </div>
</div>
