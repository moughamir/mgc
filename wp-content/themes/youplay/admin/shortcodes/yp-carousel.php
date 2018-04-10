<?php
/**
 * YP Carousel
 *
 * Example:
 * [yp_carousel style="1" width="100%" autoplay="" stage_padding="70" item_padding="0" badges_always_show="false" center="false" boxed="false"]
 *    [yp_carousel_img img_src="88" title="Image 1" href="https://nkdev.info" rating="3" badge_text="Badge 1" badge_color="default" price="$10"]
 *    [yp_carousel_img img_src="85" title="Image 2" href="https://nkdev.info" rating="5" badge_text="Badge 2" badge_color="primary" price="$14"]
 * [/yp_carousel]
 */
if ( ! function_exists( 'youplay_is_video' ) ) :
function youplay_is_video($url) {
    return strpos($url, 'youtube') > 0 || strpos($url, 'vimeo') > 0 || strpos($url, 'youtu.be') > 0;
}
endif;

call_user_func('add_' . 'shortcode', 'yp_carousel', 'yp_carousel');
if ( ! function_exists( 'yp_carousel' ) ) :
function yp_carousel($atts, $content = null) {
    if( !yp_check($content) ) {
      return '';
    }

    extract(shortcode_atts(array(
        "style"               => 1,
        "width"               => "100%",
        "autoplay"            => "",
        "stage_padding"       => 70,
        "item_padding"        => 0,
        "badges_always_show"  => false,
        "center"              => false,
        "boxed"               => false,
        "class"               => ""
    ), $atts));

    $cont_class = $class;

    $width = yp_check($width)?'style="max-width:' . esc_attr($width) . '"':'';

    // autoplay
    $autoplay = intval($autoplay);
    if($autoplay) {
      $autoplay = 'data-autoplay="' . $autoplay . '"';
    } else {
      $autoplay = '';
    }

    // extract image shortcodes
    preg_match_all( '/yp_carousel_img([^\]]+)/i', $content, $matches, PREG_OFFSET_CAPTURE );
    $items = array();
    if ( isset( $matches[1] ) ) {
      $items = $matches[1];
    }

    $before = $after = '';
    if($center && $style == 4) {
      if(yp_check($boxed)) {
        $before = '<div class="align-center container">';
      } else {
        $before = '<div class="align-center">';
      }
      $after = '</div>';
      $cont_class .= ' dib';
    } else if(yp_check($boxed)) {
      $before = '<div class="container">';
      $after = '</div>';
    }

    // each carousel item
    $result_items = '';
    foreach ( $items as $item ) {
      $item_atts = shortcode_parse_atts($item[0]);

      extract(shortcode_atts(array(
        "href"               => "",
        "img_src"            => "",
        "rating"             => "",
        "title"              => "",
        "badge_text"         => "",
        "badge_color"        => "default",
        "price"              => "",
        "class"              => ""
      ), $item_atts));

      $href = yp_check($href) ? "href='" . esc_url($href) . "'" : "";
      $title = yp_check($title) ? "<h4>" . esc_html($title) . "</h4>" : "";
      $img_full = $img_src;
      $icon = "<i class=\"fa fa-search-plus icon\"></i>";

      if(is_numeric($img_src)) {
        $img_full = wp_get_attachment_image_src( $img_src, 'full' );
        $img_full = $img_full[0];
        if(!yp_check($href) && $img_full) {
          $href = "href='" . esc_url($img_full) . "'" ;
        }
        $img_src = wp_get_attachment_image_src( $img_src, '500x375' );
        $img_src = $img_src[0];
      }

      $badge = "";
      if( yp_check($badge_text) ) {
        $badge = '<div class="badge ' . yp_sanitize_class('bg-' . $badge_color) . (yp_check($badges_always_show)?' show':'') . '">' . esc_html($badge_text) . '</div>';
      }

      // rating
      $rating = yp_get_rating(esc_html($rating));


      if( yp_check($price) ) {
        $price = '<div class="price">' . esc_html($price) . '</div>';
      }

      // video icon
      if(youplay_is_video($href)) {
        $icon = "<i class=\"fa fa-play icon\"></i>";
      }

      $item_content = '';
      switch($style) {
        case 2:
          $description = '';
          if( yp_check($price) && yp_check($rating) ) {
            $description =
              '<div class="row">
                <div class="col-xs-6">
                  ' . $rating . '
                </div>
                <div class="col-xs-6">
                  ' . $price . '
                </div>
              </div>';
          } else if( yp_check($price) ) {
            $description = $price;
          } else if( yp_check($rating) ) {
            $description = $rating;
          }

          $item_content =
            '<a class="angled-img ' . yp_sanitize_class($class) . '" ' . $href . '>
              <div class="img img-offset">
                <img src="' . esc_url($img_src) . '" alt="">
                ' . $badge . '
              </div>
              <div class="bottom-info">
                ' . $title . '
                ' . $description . '
              </div>
            </a>';
          break;

        case 3:
          $item_content =
            '<a class="angled-img ' . yp_sanitize_class($class) . '" ' . $href . '>
              <div class="img">
                <img src="' . esc_url($img_src) . '" alt="">
                ' . $badge . '
              </div>
              ' . $icon . '
            </a>';
            break;

        case 4:
          $item_content =
            '<a class="angled-img pull-left ' . yp_sanitize_class($class) . '" ' . $href . '>
              <div class="img">
                <img src="' . esc_url($img_src) . '" alt="">
              </div>
              ' . $icon . '
            </a>';
          break;

        case 5:
          $item_content =
            '<a ' . $href . ' class="' . yp_sanitize_class($class) . '">
              <img src="' . esc_url($img_src) . '" alt="">
            </a>';
          break;

        default:
          $item_content =
            '<a class="angled-img ' . yp_sanitize_class($class) . '" ' . $href . '>
              <div class="img">
                <img src="' . esc_url($img_src) . '" alt="">
                ' . $badge . '
              </div>
              <div class="over-info">
                <div>
                  <div>
                    ' . $title . '
                    ' . $rating . '
                    ' . $price . '
                  </div>
                </div>
              </div>
            </a>';
            break;
      }

      $result_items .= $item_content;

    }


    switch($style) {
      case 3:
        return $before .
            '<div class="youplay-carousel gallery-popup ' . yp_sanitize_class($cont_class) . '" ' . $width . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '">' . $result_items . '</div>' .
            $after;

      case 4:
        return $before .
            '<div class="youplay-slider gallery-popup ' . yp_sanitize_class($cont_class) . '" ' . $width . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '">
              ' . $result_items . '
            </div>' .
            $after;

      case 5:
        return $before .
            '<div class="' . yp_sanitize_class($cont_class) . '"><div class="owl-carousel" ' . $width . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '">' . $result_items . '</div></div>' .
        $after;

      default:
        return $before .
            '<div class="youplay-carousel ' . yp_sanitize_class($cont_class) . '" ' . $width . ' ' . $autoplay . ' data-stage-padding="' . esc_attr($stage_padding) . '" data-item-padding="' . esc_attr($item_padding) . '">' . $result_items . '</div>' .
        $after;
    }
}
endif;

// image for carousel
call_user_func('add_' . 'shortcode', 'yp_carousel_img', 'yp_carousel_img');
if ( ! function_exists( 'yp_carousel_img' ) ) :
function yp_carousel_img($atts, $content = null) {
    // full content inside yp_carousel shortcode
    return '';
}
endif;


/* Add VC Shortcode */
add_action( "after_setup_theme", "vc_youplay_carousel" );
if ( ! function_exists( 'vc_youplay_carousel' ) ) :
function vc_youplay_carousel() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Carousel", "youplay"),
           "base"     => "yp_carousel",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk",
           "as_parent" => array('only' => 'yp_carousel_img'),
           "content_element" => true,
           "show_settings_on_create" => false,
           "admin_enqueue_css"       => nk_admin()->admin_uri . "/shortcodes/css/yp-carousel-vc-view.css",
           "params"   => array(
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Style", "youplay"),
                 "param_name" => "style",
                 "value"      => array(
                    esc_html__("Style 1", "youplay") => 1,
                    esc_html__("Style 2", "youplay") => 2,
                    esc_html__("Style 3", "youplay") => 3,
                    esc_html__("Style 4", "youplay") => 4,
                    esc_html__("Style 5", "youplay") => 5,
                 ),
                 "description" => ""
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Width", "youplay"),
                 "param_name"  => "width",
                 "value"       => esc_html__("100%", "youplay"),
                 "description" => "",
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Autoplay", "youplay"),
                 "param_name"  => "autoplay",
                 "value"       => "",
                 "description" => esc_html__("Type integer value in ms", "youplay")
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Stage Padding", "youplay"),
                 "param_name"  => "stage_padding",
                 "value"       => 70
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Item Padding", "youplay"),
                 "param_name"  => "item_padding",
                 "value"       => 0
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Badges Always Show", "youplay"),
                 "param_name"  => "badges_always_show",
                 "value"       => array( "" => true ),
                 "description" => "When unchecked - show only on mouse over",
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Center", "youplay"),
                 "param_name"  => "center",
                 "value"       => array( "" => true ),
                 "description" => "Only for Style 4",
              ),
              array(
                 "type"        => "checkbox",
                 "heading"     => esc_html__("Boxed", "youplay"),
                 "param_name"  => "boxed",
                 "value"       => array( "" => true ),
                 "description" => "Use it when your page content boxed disabled",
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Classes", "youplay"),
                 "param_name"  => "class",
                 "value"       => "",
                 "description" => "",
              ),
           ),
           "js_view" => 'VcColumnView'
        ) );
    }
}
endif;


add_action( "after_setup_theme", "vc_youplay_carousel_img" );
if ( ! function_exists( 'vc_youplay_carousel_img' ) ) :
function vc_youplay_carousel_img() {
    if(function_exists("vc_map")) {
        /* Register shortcode with Visual Composer */
        vc_map( array(
           "name"     => esc_html__("nK Image", "youplay"),
           "base"     => "yp_carousel_img",
           "controls" => "full",
           "category" => "nK",
           "icon"     => "icon-nk",
           "as_child" => array('only' => 'yp_carousel'),
           "content_element" => true,
           "params"   => array(
              array(
                 "type" => "attach_image",
                 "heading" => esc_html__("Image", "youplay"),
                 "param_name" => "img_src",
                 "value" => "",
                 "description" => "",
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Title", "youplay"),
                 "param_name"  => "title",
                 "value"       => esc_html__("Youplay", "youplay"),
                 "description" => "Only for Style 1 and 2",
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Link", "youplay"),
                 "param_name"  => "href",
                 "value"       => "",
                 "description" => esc_html__("Leave this blank, if you want to show full image in popup in 3 and 4 carousel style. If you want to show youtube/vimeo video in popup, simply add link to this input.", "youplay"),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Rating", "youplay"),
                 "param_name"  => "rating",
                 "value"       => "",
                 "description" => esc_html__("Write number from 0 to 5. For example: 1 / 2 / 3.5 / etc... Only for Style 1 and 2", "youplay"),
              ),

              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Badge Text", "youplay"),
                 "param_name"  => "badge_text",
                 "value"       => "",
                 "description" => "",
              ),
              array(
                 "type"       => "dropdown",
                 "heading"    => esc_html__("Badge Color", "youplay"),
                 "param_name" => "badge_color",
                 "value"      => array(
                    esc_html__("Default", "youplay") => "default",
                    esc_html__("Primary", "youplay") => "primary",
                    esc_html__("Success", "youplay") => "success",
                    esc_html__("Info", "youplay")    => "info",
                    esc_html__("Warning", "youplay") => "warning",
                    esc_html__("Danger", "youplay")  => "danger",
                 ),
                 "description" => ""
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Price", "youplay"),
                 "param_name"  => "price",
                 "value"       => "",
                 "description" => esc_html__("Only for Style 1 and 2", "youplay"),
              ),
              array(
                 "type"        => "textfield",
                 "heading"     => esc_html__("Custom Classes", "youplay"),
                 "param_name"  => "class",
                 "value"       => "",
                 "description" => "",
              ),
           )
        ) );
    }
}
endif;


//Your "container" content element should extend WPBakeryShortCodesContainer class to inherit all required functionality
if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
    class WPBakeryShortCode_yp_carousel extends WPBakeryShortCodesContainer {
    }
}
if ( class_exists( 'WPBakeryShortCode' ) ) {
    class WPBakeryShortCode_yp_carousel_img extends WPBakeryShortCode {
    }
}
