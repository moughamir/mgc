(function() {
    tinymce.create('tinymce.plugins.YPButtonGroup', {
        init : function(editor, url) {
            editor.addButton('yp_button_group', {
                title   : 'YP Button Group',
                icon    : 'fa-square-o',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_button_group]<br>   [yp_button href="https://nkdev.info" size="" full_width="false" active="false" color="success" icon_before="fa fa-html5" icon_after=""]Youplay 1[/yp_button]<br>   [yp_button href="https://nkdev.info" size="" full_width="false" active="false" color="success" icon_before="fa fa-css3" icon_after=""]Youplay 2[/yp_button]<br>[/yp_button_group]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Button Group Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_button_group', tinymce.plugins.YPButtonGroup);
})();