(function() {
    tinymce.create('tinymce.plugins.YPTabs', {
        init : function(editor, url) {
            editor.addButton('yp_tabs', {
                title   : 'YP Tabs',
                icon    : 'fa-folder-o',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_tabs boxed="false"]<br>   [yp_tab title="Home" active="true"]...[/yp_tab]<br>   [yp_tab title="Other"]...[/yp_tab]<br>[/yp_tabs]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Tabs Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_tabs', tinymce.plugins.YPTabs);
})();