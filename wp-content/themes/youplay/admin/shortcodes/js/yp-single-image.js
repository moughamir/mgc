(function() {
    tinymce.create('tinymce.plugins.YPSingleImage', {
        init : function(editor, url) {
            editor.addButton('yp_single_image', {
                title   : 'YP Single Image',
                icon    : 'fa-file-image-o',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_single_image img_src="14" img_size="500x375" link_to_full_image="true" icon="fa fa-search-plus" center="false"]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Single Image Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_single_image', tinymce.plugins.YPSingleImage);
})();