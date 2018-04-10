<?php
$plugins           = TGM_Plugin_Activation::$instance->plugins;
$installed_plugins = get_plugins();

// return link to plugin
function get_plugin_actions( $item ) {
    $installed_plugins = get_plugins();

    $item['sanitized_plugin'] = $item['name'];

    $actions = array();

    // We have a repo plugin
    if ( ! $item['version'] ) {
        $item['version'] = TGM_Plugin_Activation::$instance->does_plugin_have_update( $item['slug'] );
    }

    /** We need to display the 'Install' hover link */
    if ( ! isset( $installed_plugins[$item['file_path']] ) ) {
        $actions = array(
            'install' => sprintf(
                '<a href="%1$s" class="button button-primary" title="Install %2$s">Install</a>',
                esc_url( wp_nonce_url(
                    add_query_arg(
                        array(
                            'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
                            'plugin'        => urlencode( $item['slug'] ),
                            'plugin_name'   => urlencode( $item['sanitized_plugin'] ),
                            'plugin_source' => urlencode( $item['source'] ),
                            'tgmpa-install' => 'install-plugin',
                        ),
                        TGM_Plugin_Activation::$instance->get_tgmpa_url()
                    ),
                    'tgmpa-install',
                    'tgmpa-nonce'
                ) ),
                $item['sanitized_plugin']
            ),
        );
    }
    /** We need to display the 'Activate' hover link */
    elseif ( is_plugin_inactive( $item['file_path'] ) ) {
        $actions = array(
            'activate' => sprintf(
                '<a href="%1$s" class="button button-primary" title="Activate %2$s">Activate</a>',
                esc_url( add_query_arg(
                array(
                    'plugin'               => urlencode( $item['slug'] ),
                    'plugin_name'          => urlencode( $item['sanitized_plugin'] ),
                    'plugin_source'        => urlencode( $item['source'] ),
                    'nk-activate'          => 'activate-plugin',
                    'nk-activate-nonce'    => wp_create_nonce( 'nk-activate' ),
                ),
                admin_url( 'admin.php?page=nk-theme-plugins' )
                ) ),
                $item['sanitized_plugin']
            ),
        );
    }
    /** We need to display the 'Update' hover link */
    elseif ( version_compare( $installed_plugins[$item['file_path']]['Version'], $item['version'], '<' ) ) {
        $actions = array(
            'update' => sprintf(
                '<a href="%1$s" class="button button-primary" title="Install %2$s">Update</a>',
                wp_nonce_url(
                    add_query_arg(
                        array(
                            'page'          => urlencode( TGM_Plugin_Activation::$instance->menu ),
                            'plugin'        => urlencode( $item['slug'] ),
                            'tgmpa-update'  => 'update-plugin',
                            'plugin_source' => urlencode( $item['source'] ),
                            'version'       => urlencode( $item['version'] ),
                        ),
                        TGM_Plugin_Activation::$instance->get_tgmpa_url()
                    ),
                    'tgmpa-update',
                    'tgmpa-nonce'
                ),
                $item['sanitized_plugin']
            ),
        );
    }

    elseif ( is_plugin_active( $item['file_path'] ) ) {
        $actions = array(
            'deactivate' => sprintf(
                '<a href="%1$s" class="button" title="Deactivate %2$s">Deactivate</a>',
                esc_url( add_query_arg(
                array(
                    'plugin'                 => urlencode( $item['slug'] ),
                    'plugin_name'            => urlencode( $item['sanitized_plugin'] ),
                    'plugin_source'          => urlencode( $item['source'] ),
                    'nk-deactivate'       => 'deactivate-plugin',
                    'nk-deactivate-nonce' => wp_create_nonce( 'nk-deactivate' ),
                ),
                admin_url( 'admin.php?page=nk-theme-plugins' )
                ) ),
                $item['sanitized_plugin']
            ),
        );
    }

    return $actions;
}

?>
<p class="about-description"><?php esc_html_e('These plugins comes with Youplay theme. Required only nK Themes Helper and Visual Composer plugins. If you want full functionality from demo page, you should activate all of these plugins.', 'youplay'); ?></p>
<div class="feature-section theme-browser nk-plugins-list">
    <?php foreach ( $plugins as $plugin ) : ?>
        <?php
        $plugin_status = '';
        $plugin_action = get_plugin_actions( $plugin );

        if ( is_plugin_active( $plugin['file_path'] ) ) {
            $plugin_status = 'active';
        }
        ?>
        <div class="theme">
            <div class="theme-wrapper">
                <div class="theme-screenshot">
                    <img src="<?php echo $plugin['thumbnail']; ?>" alt="" />
                    <div class="plugin-info">
                        <?php if ( isset( $installed_plugins[ $plugin['file_path'] ] ) ) : ?>
                            <?php printf( esc_html__( 'Version: %1s | ', 'youplay' ) . '<a href="%2s" target="_blank">%3s</a>', $installed_plugins[ $plugin['file_path'] ]['Version'], $installed_plugins[ $plugin['file_path'] ]['AuthorURI'], $installed_plugins[ $plugin['file_path'] ]['Author'] ); ?>
                        <?php elseif ( 'bundled' == $plugin['source_type'] ) : ?>
                            <?php printf( esc_attr__( 'Available Version: %s', 'youplay' ), $plugin['version'] ); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <h3 class="theme-name">
                    <?php echo $plugin['name']; ?>
                </h3>
                <div class="theme-actions">
                    <?php foreach ( $plugin_action as $action ) { echo $action; } ?>
                </div>
                <?php if ( isset( $plugin_action['update'] ) && $plugin_action['update'] ) : ?>
                    <div class="theme-update">
                        <?php printf( esc_html__( 'Update Available: Version %s', 'youplay' ), $plugin['version'] ); ?>
                    </div>
                <?php endif; ?>
                <?php if ( isset( $plugin['required'] ) && $plugin['required'] ) : ?>
                    <div class="plugin-required">
                        <?php esc_html_e( 'Required', 'youplay' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
