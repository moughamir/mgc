(function() {
    tinymce.create('tinymce.plugins.YPText', {
        init : function(editor, url) {
            editor.addButton('yp_text', {
                title   : 'YP Text',
                icon    : 'fa-font',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_text boxed="false"]My Text[/yp_text]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Text Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_text', tinymce.plugins.YPText);
})();