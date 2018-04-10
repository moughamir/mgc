(function() {
    tinymce.create('tinymce.plugins.YPAccordion', {
        init : function(editor, url) {
            editor.addButton('yp_accordion', {
                title   : 'YP Accordion',
                icon    : 'fa-list',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_accordion boxed="false"]<br>   [yp_accordion_tab title="Home" active="true"]...[/yp_accordion_tab]<br>   [yp_accordion_tab title="Other"]...[/yp_accordion_tab]<br>[/yp_accordion]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Accordion Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_accordion', tinymce.plugins.YPAccordion);
})();