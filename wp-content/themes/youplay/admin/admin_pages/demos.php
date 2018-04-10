<?php

$demos = array(
    'dark' => array(
        'preview' => 'https://wp.nkdev.info/youplay/main/'
    ),
    'shooter' => array(
        'preview' => 'https://wp.nkdev.info/youplay/demos/shooter/'
    ),
    'anime' => array(
        'preview' => 'https://wp.nkdev.info/youplay/demos/anime/'
    ),
    'light' => array(
        'preview' => 'https://wp.nkdev.info/youplay/demos/light/'
    )
);
add_thickbox();

if(!function_exists('nk_theme')) {
    ?>
    <p class="about-description">
        <mark class="error">
            <?php esc_html_e( 'You should install and activate required plugin nK Themes Helper. Find it in "Plugins" tab.', 'youplay' ); ?>
        </mark>
    </p>
    <?php
    return;
}

?>

<div class="about-description">
    Important Notes:
    <ol>
        <li>We recommend import demo on a clean WordPress website. To reset your installation use <a href="plugin-install.php?tab=plugin-information&amp;plugin=wordpress-reset&amp;TB_iframe=true&amp;width=750&amp;height=600" class="thickbox">Reset WordPress Plugin</a>.</li>
        <li>All recommended and required plugin should be installed.</li>
        <li>After demo data imported, run <a href="plugin-install.php?tab=plugin-information&amp;plugin=regenerate-thumbnails&amp;TB_iframe=true&amp;width=750&amp;height=600" class="thickbox">Regenerate Thumbnails</a> plugin.</li>
        <li>Importing a demo provides images, pages, posts, theme options, widgets and more. . Please, wait before the process end, it may take a while.</li>
    </ol>
</div>

<div class="nk-import-result"></div>

<div class="feature-section theme-browser nk-demos-list">
    <?php
    // Loop through all demos
    foreach ( $demos as $name => $demo ) { ?>
        <div class="theme">
            <div class="theme-wrapper">
                <div class="theme-screenshot">
                    <img src="<?php echo get_template_directory_uri() . '/admin/assets/images/demos/' . $name . '.jpg'; ?>" />
                </div>
                <h3 class="theme-name" id="<?php echo $name; ?>"><?php echo ucwords( str_replace( '_', ' ', $name ) ); ?></h3>
                <div class="theme-actions">
                    <?php printf( '<a class="button" target="_blank" href="%1s">%2s</a>', $demo['preview'], esc_html__( 'Preview', 'youplay' ) ); ?>
                    <?php printf( '<a class="button button-primary button-demo" data-demo="%s" href="#">%s</a>', strtolower( $name ), esc_html__( 'Import', 'youplay' ) ); ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
