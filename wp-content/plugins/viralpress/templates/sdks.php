<?php
/**
 * @ViralPress 
 * @Wordpress Plugin
 * @author InspiredDev <iamrock68@gmail.com>
 * @copyright 2016
*/
defined( 'ABSPATH' ) || exit;
?>
<!--facebook_sdk-->
<?php if( !empty( $attributes['load_fb']) || empty($attributes) ) : ?>
    <div id="fb-root"></div>
    <?php if( !empty( $vp_instance->settings['fb_app_id']) ):?>
    <script>
    window.fbAsyncInit = function() {
        FB.init({
          appId      : '<?php echo $vp_instance->settings['fb_app_id']?>',
          xfbml      : true,
          version    : 'v2.5'
        });
      };
    </script>		
    <?php endif;?>
    <script>
    (function(d, s, id){
         var js, fjs = d.getElementsByTagName(s)[0];
         if (d.getElementById(id)) {return;}
         js = d.createElement(s); js.id = id;
         js.src = "https://connect.facebook.net/en_US/sdk.js";
         fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));
    </script>
<?php endif;?>

<?php if( !empty( $attributes['load_tw']) || empty($attributes) ) : ?>
    <!--twitter_sdk-->
    <script>window.twttr = (function(d, s, id) {
      var js, fjs = d.getElementsByTagName(s)[0],
        t = window.twttr || {};
      if (d.getElementById(id)) return t;
      js = d.createElement(s);
      js.id = id;
      js.src = "https://platform.twitter.com/widgets.js";
      fjs.parentNode.insertBefore(js, fjs);
     
      t._e = [];
      t.ready = function(f) {
        t._e.push(f);
      };
      return t;
    }(document, "script", "twitter-wjs"));</script>
<?php endif;?>

<?php if( !empty( $attributes['load_pin']) || empty($attributes) ) : ?>
    <!--pinterest_sdk-->
    <script async defer src="//assets.pinterest.com/js/pinit.js" data-pin-build="refreshPin"></script>
<?php endif;?>

<?php if( !empty( $attributes['load_vine']) || empty($attributes) ) : ?>
    <!--vine_sdk-->
    <script async defer src="https://platform.vine.co/static/scripts/embed.js"></script>
<?php endif;?>

<?php if( !empty( $attributes['load_goo']) || empty($attributes) ) : ?>
    <!--google_sdk-->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <script type="text/javascript">
      (function() {
       var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
       po.src = 'https://apis.google.com/js/client.js?onload=GoggleOnLoad';
       var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
     })();
    </script>
<?php endif;?>