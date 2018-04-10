(function($) {
    // init tether drop
    $('.nk-drop').each(function () {
        new Drop({
            target: this,
            content: $(this).children('.nk-drop-cont').hide().html(),
            classes: 'nk-drop-tether drop-theme-arrows drop-theme-twipsy',
            position: 'bottom center',
            openOn: 'hover'
        })
    });

    function GET(name) {
        var match = RegExp('[?&]' + name + '=([^&]*)').exec(window.location.search);
        return match && decodeURIComponent(match[1].replace(/\+/g, ' '));
    }

    // Activate nk admin menu theme option entry when theme options are active
    var $appearance_menu = $('#menu-appearance');
    $appearance_menu.children('.wp-submenu').find('a[href*="ot-theme-options"]').parent().remove();
    if ( GET('page') === 'ot-theme-options' ) {
        var $nk_admin_menu = $('#toplevel_page_nk-theme');
        $nk_admin_menu.addClass('wp-has-current-submenu wp-menu-open');
        $nk_admin_menu.children('a').addClass('wp-has-current-submenu wp-menu-open');
        $appearance_menu.removeClass('wp-has-current-submenu wp-menu-open');
        $appearance_menu.addClass('wp-not-current-submenu');
        $appearance_menu.find('a[href="themes.php"]').removeClass('wp-has-current-submenu wp-menu-open');
        $appearance_menu.find('a[href="themes.php"]').addClass('wp-not-current-submenu');
    }

    // import demo
    if($('.nk-demos-list').length) {
        $('.nk-demos-list .theme-wrapper').append('<div class="nk-theme-demo-icon"></div>');
        var loadingIcon = '<i class="dashicons dashicons-update"></i>';
        var successIcon = '<i class="dashicons dashicons-thumbs-up"></i>';
        var warningIcon = '<i class="dashicons dashicons-warning"></i>';
        var successMessage = 'Demo data successfully imported. Now, please install and run <a href="plugin-install.php?tab=plugin-information&plugin=regenerate-thumbnails&TB_iframe=true&width=750&height=600" class="thickbox">Regenerate Thumbnails</a> plugin once.';
        var errorMessage = '<strong>We\'re sorry but the demo data could not be imported. It is most likely due to low PHP configurations on your server.</strong><br>Please open "Dashboard" tab and check that there are no notices marked RED, then use the <a href="plugin-install.php?tab=plugin-information&plugin=wordpress-reset&TB_iframe=true&width=750&height=600" class="thickbox">Reset WordPress Plugin</a>, then import again.';
        var $demoIcons = $('.nk-theme-demo-icon');
        var $description = $('.about-description');
        var busyImport = 0;

        $('.nk-demos-list .button-demo').on( 'click', function(e) {
            e.preventDefault();
            if(busyImport) {
                return;
            }

            var $demoIcon = $(this).parents('.theme:eq(0)').find('.nk-theme-demo-icon');
            var $confirm = window.confirm( 'This will import data from demo page. Clicking this option will replace your current theme options and widgets. Please, wait before the process end. It may take a while.' );

            if ($confirm == true) {
                $demoIcons.html('');
                $demoIcon.html(loadingIcon);
                $demoIcons.addClass('show');
                busyImport = 1;

                $.post(ajaxurl, {
                    action: 'nk_demo_import_action',
                    demo_name: $(this).attr('data-demo')
                }, function (response) {
                    if($.trim(response) == '' || !response.length) {
                        $description.html(errorMessage);
                        $demoIcon.html(warningIcon);
                    } else {
                        $description.html(successMessage);
                        $demoIcon.html(successIcon);
                        $('.nk-import-result').html(response).slideDown();
                    }
                    setTimeout(function () {
                        $demoIcons.removeClass('show');
                        busyImport = 0;
                    }, 3000);
                });
            }
        });

        // prevent user leaving page
        $(window).on('beforeunload', function () {
            if(busyImport) {
                return "The demo import process is not finished yet...";
            }
        });
    }

    // activate theme copy by purchase key
    var busy_activate;
    $('#nk-theme-activate').on('click', function () {
        if(busy_activate) {
            return;
        }
        var code = $('#nk-theme-activate-license').val();
        var token = $('#nk-theme-activate-token').val();
        var refresh_token = $('#nk-theme-refresh-token').val();
        if(code && token && refresh_token) {
            busy_activate = 1;
            $(this).attr('disabled', 'disabled');
            $(this).append(' <span class="nk-theme-update-icon"><i class="dashicons dashicons-update"></i></span>');

            $.post(ajaxurl, {
                action: 'nk_activation_action',
                type: 'activate',
                code: code,
                token: token,
                refresh_token: refresh_token
            }, function (response) {
                if(response === 'ok') {

                } else {
                    alert(response);
                }
                // reload
                var reloadUri = $('#nk-theme-activate-reload').val();
                location.href = reloadUri || location.href;
            });
        }
    });

    // deactivate theme
    var busy_deactivate;
    $('#nk-theme-deactivate-license').on('click', function () {
        if(busy_deactivate) {
            return;
        }
        var $confirm = window.confirm( 'This will deactivate your copy of theme. You will not be able to get direct theme updates.' );

        if($confirm == true) {
            busy_deactivate = 1;
            $(this).attr('disabled', 'disabled');
            $(this).append(' <span class="nk-theme-update-icon"><i class="dashicons dashicons-update"></i></span>');

            $.post(ajaxurl, {
                action: 'nk_activation_action',
                type: 'deactivate'
            }, function (response) {
                if(response === 'ok') {

                } else {
                    alert(response);
                }
                // reload
                var reloadUri = $('#nk-theme-deactivate-reload').val();
                location.href = reloadUri || location.href;
            });
        }
    });
}(jQuery));
