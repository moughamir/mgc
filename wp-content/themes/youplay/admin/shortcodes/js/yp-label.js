(function() {
    tinymce.create('tinymce.plugins.YPLabel', {
        init : function(editor, url) {
            editor.addButton('yp_label', {
                title   : 'YP Label',
                icon    : 'fa-tag',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_label color="default" text="Label"]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Label Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_label', tinymce.plugins.YPLabel);
})();