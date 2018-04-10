(function() {
    tinymce.create('tinymce.plugins.YPCountdown', {
        init : function(editor, url) {
            editor.addButton('yp_countdown', {
                title   : 'YP Countdown',
                icon    : 'fa-clock-o',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_countdown style="default" custom="" date="11/28/2017"]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Countdown Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_countdown', tinymce.plugins.YPCountdown);
})();