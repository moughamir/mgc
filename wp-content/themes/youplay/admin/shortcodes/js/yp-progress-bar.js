(function() {
    tinymce.create('tinymce.plugins.YPProgressBar', {
        init : function(editor, url) {
            editor.addButton('yp_progress_bar', {
                title   : 'YP Progress Bar',
                icon    : 'fa-tasks',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_progress stripped="true" percent="40" color="success" boxed="false"]40% Complete (success)[/yp_progress]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Progress Bar Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_progress_bar', tinymce.plugins.YPProgressBar);
})();