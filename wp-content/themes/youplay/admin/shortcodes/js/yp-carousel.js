(function() {
    tinymce.create('tinymce.plugins.YPCarousel', {
        init : function(editor, url) {
            editor.addButton('yp_carousel', {
                title   : 'YP Carousel',
                icon    : 'fa-refresh',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_carousel style="1" width="100%" badges_always_show="false" center="false" boxed="false"]<br>   [yp_carousel_img img_src="88" title="Image 1" href="https://nkdev.info" rating="3" badge_text="Badge 1" badge_color="default" price="$10"]<br>   [yp_carousel_img img_src="85" title="Image 2" href="https://nkdev.info" rating="5" badge_text="Badge 2" badge_color="primary" price="$14"]<br>[/yp_carousel]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Carousel Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_carousel', tinymce.plugins.YPCarousel);
})();