(function() {
    tinymce.create('tinymce.plugins.YPRecentPosts', {
        init : function(editor, url) {
            editor.addButton('yp_recent_posts', {
                title   : 'YP Recent Posts',
                icon    : 'fa-thumb-tack',
                onclick : function() {
                    editor.execCommand('mceInsertContent', false, '[yp_recent_posts style="1" post_type="post" ids="" exclude_ids="" custom_query="" count="5" pagination="false" boxed="false"]');
                }
            });
        },
        createControl : function(n, cm) {
            return null;
        },
        getInfo : function() {
            return {
                longname  : "YP Recent Posts Shortcode",
                author    : 'nK',
                authorurl : 'https://nkdev.info/',
                infourl   : 'https://nkdev.info/',
                version   : "1.0"
            };
        }
    });
    tinymce.PluginManager.add('yp_recent_posts', tinymce.plugins.YPRecentPosts);
})();