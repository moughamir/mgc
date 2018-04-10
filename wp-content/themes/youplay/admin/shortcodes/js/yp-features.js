(function() {
    tinymce.create('tinymce.plugins.YPFeatures', {
        init : function(editor, url) {
            editor.addButton('yp_features', {
                title   : 'YP Features',
                icon    : 'fa-th-large',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_features title="Youplay" description="Description" icon="fa fa-css3" boxed="false"]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Features Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_features', tinymce.plugins.YPFeatures);
})();