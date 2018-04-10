<?php
// minimum requirements
$min_requirements = array(
    'php_version' => '5.3.0',
    'memory_limit' => '256M',
    'max_execution_time' => 180
);
?>

<div class="nk-dashboard-widgets">
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <?php
                if(nk_admin()->activation()->active) {
                    ?>
                    <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e('Activated', 'youplay'); ?></span>
                    <mark class="yes"><?php esc_html_e('Activation', 'youplay'); ?></mark>
                    <?php
                } else {
                    ?>
                    <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('Not Activated', 'youplay'); ?></span>
                    <mark class="error"><?php esc_html_e('Activation', 'youplay'); ?></mark>
                    <?php
                }
            ?>
        </div>
        <div class="nk-dashboard-widget-content">
            <p>
                By activating <?php echo nk_admin()->theme_name; ?> you will unlock premium options - <strong>direct theme updates</strong> and access to <strong>official support</strong>.
            </p>

            <?php
                $new_purchase_codes = nk_admin()->activation()->new_purchase_codes;
                $new_token = nk_admin()->activation()->new_token;
                $new_refresh_token = nk_admin()->activation()->new_refresh_token;
                if(nk_admin()->activation()->active) {
                    ?>
                    <p>
                        <span id="nk-theme-deactivate-license" class="button button-secondary" target="_blank">Deactivate <?php echo nk_admin()->theme_name; ?></span>
                        <input id="nk-theme-deactivate-reload" type="hidden" value="<?php echo esc_attr(admin_url('admin.php?page=nk-theme')); ?>">
                    </p>
                    <?php
                } else if((is_array($new_purchase_codes) || $new_purchase_codes == 'false') && $new_token && $new_refresh_token) {
                    // if no one license found
                    if($new_purchase_codes == 'false' || count($new_purchase_codes) == 0) {
                        ?>
                        <p>
                            <mark class="error">You haven't any valid license.</mark>
                            <em><a href="https://wp.nkdev.info/_api/?item_id=<?php echo nk_admin()->theme_id; ?>&type=redirect" target="_blank">Purchase <?php echo nk_admin()->theme_name; ?> License</a></em>
                        </p>
                        <?php
                    }

                    // if have one license
                    else if (count($new_purchase_codes) == 1) {
                        ?>
                        <p>
                            You have <strong>1</strong> valid license at <strong><?php echo esc_html($new_purchase_codes[0]['sold_at']); ?></strong>.
                        </p>
                        <input id="nk-theme-activate-license" type="text" value="<?php echo esc_attr($new_purchase_codes[0]['code']); ?>" disabled>
                        <input id="nk-theme-activate-reload" type="hidden" value="<?php echo esc_attr(admin_url('admin.php?page=nk-theme')); ?>">
                        <input id="nk-theme-activate-token" type="hidden" value="<?php echo esc_attr($new_token); ?>">
                        <input id="nk-theme-refresh-token" type="hidden" value="<?php echo esc_attr($new_refresh_token); ?>">
                        <p></p>
                        <span id="nk-theme-activate" class="button button-primary">Activate</span>
                        <?php
                    }

                    // if > 1 license keys
                    else if (count($new_purchase_codes) > 1) {
                        ?>
                        <p>
                            You have <strong><?php echo count($new_purchase_codes); ?></strong> valid licenses. Choose one:
                        </p>
                        <select id="nk-theme-activate-license">
                            <?php
                            foreach ($new_purchase_codes as $key) {
                                ?><option value="<?php echo esc_attr($key['code']); ?>">
                                    <?php echo esc_html($key['code']); ?> - <?php echo esc_html($key['sold_at']); ?>
                                </option><?php
                            }
                            ?>
                        </select>
                        <input id="nk-theme-activate-reload" type="hidden" value="<?php echo esc_attr(admin_url('admin.php?page=nk-theme')); ?>">
                        <input id="nk-theme-activate-token" type="hidden" value="<?php echo esc_attr($new_token); ?>">
                        <input id="nk-theme-refresh-token" type="hidden" value="<?php echo esc_attr($new_refresh_token); ?>">
                        <p></p>
                        <span id="nk-theme-activate" class="button button-primary">Activate</span>
                        <?php
                    }
                } else {
                    ?>
                    <p>
                        <a href="<?php echo esc_attr('https://wp.nkdev.info/_api?item_id=' . nk_admin()->theme_id . '&type=activation_check&redirect_uri=' . urlencode(admin_url('admin.php?page=nk-theme'))); ?>" class="button button-primary">Activate <?php echo nk_admin()->theme_name; ?></a>
                    </p>
                    <p>
                        <em>Don't have valid license yet? <a href="https://wp.nkdev.info/_api/?item_id=<?php echo nk_admin()->theme_id; ?>&type=redirect" target="_blank">Purchase <?php echo nk_admin()->theme_name; ?> License</a></em>
                    </p>
                    <?php
                }
            ?>
        </div>
    </div>
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <?php
            if(nk_admin()->updater()->is_update_available()) {
                ?>
                <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('Update Available', 'youplay'); ?></span>
                <mark class="error"><?php esc_html_e('Update', 'youplay'); ?></mark>
                <?php
            } else {
                ?>
                <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e('Theme is Up to Date', 'youplay'); ?></span>
                <mark class="yes"><?php esc_html_e('Update', 'youplay'); ?></mark>
                <?php
            }
            ?>
        </div>
        <div class="nk-dashboard-widget-content">
            <p>
                <strong><?php _e( 'Installed Version:', 'youplay' ); ?></strong>
                <br>
                <?php echo nk_admin()->theme_version; ?>
            </p>
            <p>
                <strong><?php _e( 'Latest Version:', 'youplay' ); ?></strong>
                <br>
                <?php echo nk_admin()->updater()->get_latest_theme_version(); ?>
            </p>
            <?php
                if(nk_admin()->updater()->is_update_available()) {
                    if(nk_admin()->activation()->active) {
                        $update_url = wp_nonce_url( admin_url('update.php?action=upgrade-theme&amp;theme=' . urlencode(nk_admin()->theme_slug)), 'upgrade-theme_' . nk_admin()->theme_slug );
                        ?>
                        <a href="<?php echo esc_attr($update_url); ?>" class="button button-primary">Update Now</a>
                        <?php
                    } else {
                        ?>
                        <span class="button button-primary disabled">Update Now</span>
                        <?php
                    }
                } else {
                    ?>
                    <span class="button disabled">Update Now</span>
                    <?php
                }
            ?>
        </div>
    </div>
    <div class="nk-dashboard-widget">
        <?php

        // requirements check
        $memory = nk_admin()->let_to_num( WP_MEMORY_LIMIT );
        $min_memory = nk_admin()->let_to_num( $min_requirements['memory_limit'] );
        $req_memory_limit = $memory >= $min_memory;

        $req_php_ver = true;
        if(function_exists('phpversion')) {
            $php_ver = phpversion();
            $req_php_ver = version_compare($php_ver, $min_requirements['php_version'], '>=');
        }

        $req_max_exec_time = true;
        if(function_exists('ini_get')) {
            $time_limit = ini_get('max_execution_time');
            $req_max_exec_time = $time_limit >= $min_requirements['max_execution_time'];
        }

        $req_all_ok = $req_memory_limit && $req_php_ver && $req_max_exec_time;

        ?>

        <div class="nk-dashboard-widget-title">
            <?php
            if($req_all_ok) {
                ?>
                <span class="nk-dashboard-widget-title-badge yes"><i class="fa fa-thumbs-up"></i> <?php esc_html_e('No Problems', 'youplay'); ?></span>
                <mark class="yes"><?php esc_html_e('Requirements', 'youplay'); ?></mark>
                <?php
            } else {
                ?>
                <span class="nk-dashboard-widget-title-badge error"><i class="fa fa-exclamation-triangle"></i> <?php esc_html_e('Some Problems', 'youplay'); ?></span>
                <mark class="error"><?php esc_html_e('Requirements', 'youplay'); ?></mark>
                <?php
            }
            ?>
        </div>
        <div class="nk-dashboard-widget-content">
            <div class="nk-theme-requirements">
                <table class="widefat" cellspacing="0">
                    <tbody>
                        <tr>
                            <td><?php _e( 'WP Memory Limit:', 'youplay' ); ?></td>
                            <td><?php
                            if ($req_memory_limit) {
                                echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . size_format($memory) . '</mark>';
                            } else {
                                echo '<mark class="error nk-drop"><i class="fa fa-times-circle"></i> ' . size_format($memory) . ' ';
                                echo '<small>[more info]</small>';
                                echo '<span class="nk-drop-cont">';
                                echo sprintf(
                                        esc_html__( 'For normal usage will be enough 128 MB, but for importing demo we recommend setting memory to at least %s.', 'youplay' ),
                                        '<strong>' . size_format($min_memory) . '</strong>'
                                    );
                                echo ' <br> ';
                                echo sprintf(
                                        esc_html__( 'See more: %s', 'youplay' ),
                                        sprintf('<a href="http://codex.wordpress.org/Editing_wp-config.php#Increasing_memory_allocated_to_PHP" target="_blank">%s</a>', esc_html__('Increasing memory allocated to PHP.', 'youplay'))
                                    );
                                echo '</span>';
                                echo '</mark>';
                            }
                            ?></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'PHP Version:', 'youplay' ); ?></td>
                            <td><?php
                                if (function_exists('phpversion')) {
                                    if ($req_php_ver) {
                                        echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . $php_ver . '</mark>';
                                    } else {
                                        echo '<mark class="error nk-drop">';
                                        echo '<i class="fa fa-times-circle"></i> ' . $php_ver;
                                        echo ' <small>[more info]</small>';
                                        echo '<span class="nk-drop-cont">';
                                        echo sprintf( esc_html__( 'We recommend upgrade php version to at least %s.', 'youplay' ), $min_requirements['php_version'] );
                                        echo '</span>';
                                        echo '</mark>';
                                    }
                                }
                            ?></td>
                        </tr>
                        <tr>
                            <td><?php _e( 'PHP Time Limit:', 'youplay' ); ?></td>
                            <td>
                            <?php if (function_exists('ini_get')) :
                                if ($req_max_exec_time) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i> ' . $time_limit . '</mark>';
                                } else {
                                    echo '<mark class="error nk-drop">';
                                    echo '<i class="fa fa-times-circle"></i> ' . $time_limit;
                                    echo ' <small>[more info]</small>';
                                    echo '<span class="nk-drop-cont">';
                                    echo sprintf( esc_html__( 'We recommend setting max execution time to at least %s.', 'youplay' ), $min_requirements['max_execution_time'] );
                                    echo ' <br> ';
                                    echo sprintf(
                                        esc_html__('See more: %s', 'youplay'),
                                        sprintf('<a href="http://codex.wordpress.org/Common_WordPress_Errors#Maximum_execution_time_exceeded" target="_blank">%s</a>', esc_html__('Increasing max execution to PHP', 'youplay'))
                                    );
                                    echo '</span>';
                                    echo '</mark>';
                                }
                            endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td><?php _e( 'Child Theme:', 'youplay' ); ?></td>
                            <td><?php
                                if(nk_admin()->theme_is_child) {
                                    echo '<mark class="yes"><i class="fa fa-check-circle"></i></mark>';
                                } else {
                                    ?>
                                    <mark class="nk-drop">
                                        <i class="fa fa-times-circle"></i>
                                        <small>[more info]</small>
                                        <span class="nk-drop-cont">
                                            <?php esc_html_e('We recommend use child theme to prevent loosing your customizations after theme update.', 'youplay'); ?>
                                        </span>
                                    </mark>
                                    <?php
                                }
                            ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="nk-dashboard-widget">
        <div class="nk-dashboard-widget-title">
            <mark><?php esc_html_e('Support', 'youplay'); ?></mark>
        </div>
        <div class="nk-dashboard-widget-content">
            <p><?php esc_html_e('Have troubles, found a bug or want to suggest something? Write in support system.', 'youplay'); ?></p>

            <?php
                if(nk_admin()->activation()->active) {
                    ?>
                    <p>
                        <em><?php echo esc_html__('Your purchase code to get support:', 'youplay'); ?></em>
                        <br>
                        <strong><?php echo nk_admin()->activation()->purchase_code; ?></strong>
                    </p>
                    <?php
                    printf('<a href="%s" class="button button-primary" target="_blank">%s</a>', 'https://nk.ticksy.com/', esc_html__('Get Support', 'youplay'));
                } else {
                    ?>
                    <p><em><?php esc_html_e('Make sure, you have valid license, otherwise I don\'t provide support.', 'youplay'); ?></em></p>
                    <?php
                    printf('<span class="button disabled">%s</span>', esc_html__('Get Support', 'youplay'));
                }
            ?>
        </div>
    </div>
</div>
