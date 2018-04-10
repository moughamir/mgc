<?php
/**
 * Instagram API
 * How to generate access token (or use google) - http://instagram.pixelunion.net/
 *
 * Example:
 *   nk_theme()->instagram()->set_data(array(
 *       'access_token' => '2955800576.e6b770c.298a4ea57ed94bf6be27544740bd10eb',
 *       'user_id'      => '10365980',
 *       'cachetime'    => 3700
 *   ));
 *   $instagram = nk_theme()->instagram()->get_instagram(10);
 */
if (!class_exists('nK_Helper_Instagram')) :
class nK_Helper_Instagram {
    /**
     * The single class instance.
     */
    private static $_instance = null;

    /**
    * Main Instance
    * Ensures only one instance of this class exists in memory at any one time.
    */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {
        /* We do nothing here! */
    }

    private $access_token;
    private $user_id;

    private $hash;
    private $cacheName;
    private $cacheTime;

    private $cachetime;
    private $error;

    public function set_data ($data = array()) {
        $default = array(
            'access_token' => '',
            'user_id'      => '',
            'cachetime'    => 3700
        );
        $data = array_merge($default, $data);
        $this->access_token = $data['access_token'];
        $this->user_id = $data['user_id'];

        $this->cachetime = (int) $data['cachetime'] ? $data['cachetime'] * 60 : 0;

        // create names to store in database
        $this->hash = md5(json_encode(array(
            $this->access_token,
            $this->user_id
        )));
        $this->cacheName = 'instagram-backup-' . $this->hash;
    }

    public function get_instagram ($count = 6) {
        $cache_name = $this->cacheName . $count;
        $result = nk_theme()->get_cache($cache_name);
        if ($result) {
            return $result;
        }

        $result = $this->get('https://api.instagram.com/v1/users/' . $this->user_id . '/media/recent/?access_token=' . $this->access_token . '&count=' . $count);

        if (!(isset($result->errors) && count($result->errors)>0) && $result) {
            // Fetch succeeded.
            nk_theme()->set_cache($cache_name, $result->data, $this->cachetime);
            return $result->data;
        } else {
            if ($result) {
                $this->set_error( array_shift($result->errors) );
            }
            nk_theme()->clear_cache($cache_name);
            return false;
        }
    }

    public function get ($url) {
        // Make Requests
        $options_buf = wp_remote_get($url);

        if (!is_wp_error($options_buf) && isset($options_buf['body'])) {
            return json_decode($options_buf['body']);
        } else {
            return false;
        }
    }

    public function has_error () {
        return !empty($this->error);
    }

    public function get_error () {
        return $this->error;
    }

    public function set_error ($error) {
        $this->error = $error;
    }
}
endif;
