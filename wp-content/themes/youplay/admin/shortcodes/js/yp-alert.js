(function() {
    tinymce.create('tinymce.plugins.YPAlert', {
        init : function(editor, url) {
            editor.addButton('yp_alert', {
                title   : 'YP Alert',
                icon    : 'fa-exclamation-triangle',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_alert color="primary" dismissible="false" boxed="false"]<strong>Well done!</strong> You successfully read this important alert message.[/yp_alert]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Alert Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_alert', tinymce.plugins.YPAlert);
})();