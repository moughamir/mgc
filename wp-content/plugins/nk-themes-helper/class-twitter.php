<?php
/**
 * Twitter API
 * How to create Twitter API keys you can read here (or use google):  - http://www.gabfirethemes.com/create-twitter-api-key/
 *
 * Example:
 *   nk_theme()->twitter()->set_data(array(
 *       'consumer_key'         => 'nTonrwrwq5eYGolV4OphwnneX',
 *       'consumer_secret'      => 'LGWplFGsXEWd09noQzDLDAmg8mJL563fUr3J9EnKLaJeREX4R7',
 *       'access_token'         => '2848523763-dNhSczJJcQ2rXjOIItT7Pue4RidjtRu3xB1NVcg',
 *       'access_token_secret'  => 'U0mKRISDYmWcU51I87grlOF7sLO3mgvE84Sr1Ncn5VnSm',
 *       'cachetime'            => 3700
 *   ));
 *   $tweets = nk_theme()->twitter()->get_tweets(10, true);
 */
if (!class_exists('nK_Helper_Twitter')) :
class nK_Helper_Twitter {
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
    private $access_token_secret;
    private $consumer_key;
    private $consumer_secret;

    private $hash;
    private $cacheName;

    private $cachetime;
    private $error;

    public function set_data ($data = array()) {
        $default = array(
            'consumer_key'         => '',
            'consumer_secret'      => '',
            'access_token'         => '',
            'access_token_secret'  => '',
            'cachetime'            => 3700
        );
        $data = array_merge($default, $data);
        $this->consumer_key = $data['consumer_key'];
        $this->consumer_secret = $data['consumer_secret'];
        $this->access_token = $data['access_token'];
        $this->access_token_secret = $data['access_token_secret'];

        $this->cachetime = (int) $data['cachetime'] ? $data['cachetime'] * 60 : 0;

        // create names to store in database
        $this->hash = md5(json_encode(array(
            $this->consumer_key,
            $this->consumer_secret,
            $this->access_token,
            $this->access_token_secret
        )));
        $this->cacheName = 'twitter-backup-' . $this->hash;
    }

    public function get_tweets ($count = 5, $show_replies = true) {
        $cache_name = $this->cacheName . $count . ($show_replies ? 1 : 0);
        $result = nk_theme()->get_cache($cache_name);
        if ($result) {
            return $result;
        }

        $result = array();
        $fetchedTweets = $this->get('https://api.twitter.com/1.1/statuses/user_timeline.json');

        if (!(isset($fetchedTweets->errors) && count($fetchedTweets->errors)>0) && $fetchedTweets) {
            // Fetch succeeded.
            $limitToDisplay = min($count, count($fetchedTweets));

            for ($i = 0; $i < count($fetchedTweets); $i++) {
                // check for replies
                if(!$show_replies && $fetchedTweets[$i]->in_reply_to_user_id) {
                    continue;
                }

                $result[$i] = $fetchedTweets[$i];

                // user friendly date
                $date = date_parse($result[$i]->created_at);
                if($date['year'] == date('Y')) {
                    $date = date('j F', strtotime($result[$i]->created_at));
                } else {
                    $date = date('j F Y', strtotime($result[$i]->created_at));
                }
                $result[$i]->date_formatted = $date;

                // text with links
                $result[$i]->text_entitled = $this->add_tweet_entity_links($result[$i]);

                if(count($result) >= $limitToDisplay) {
                    break;
                }
            }

            nk_theme()->set_cache($cache_name, $result, $this->cachetime);
            return $result;
        } else {
            if ($fetchedTweets) {
                $this->set_error( array_shift($fetchedTweets->errors) );
            }
            nk_theme()->clear_cache($cache_name);

            return false;
        }
    }

    public function get_followers_count () {
        $result = nk_theme()->get_cache($this->cacheName . '-followers');

        if ($result) {
            return $result;
        }

        $res = $this->get('https://api.twitter.com/1.1/statuses/user_timeline.json');
        if (!(isset($res->errors) && count($res->errors)>0) && $res) {
            $el = array_shift($res);
            $result = intval($el->user->followers_count);

            nk_theme()->set_cache($this->cacheName . '-followers', $result, $this->cachetime);

            return $result;
        }  else {
            if ($res) {
                $this->set_error( array_shift($res->errors) );
            }
            nk_theme()->clear_cache($this->cacheName . '-followers');

            return false;
        }
    }

    public function get ($url = 'https://api.twitter.com/1.1/statuses/user_timeline.json') {
        $oauth = array( 'oauth_consumer_key' => $this->consumer_key,
        'oauth_nonce' => time(),
        'oauth_signature_method' => 'HMAC-SHA1',
        'oauth_token' => $this->access_token,
        'oauth_timestamp' => time(),
        'oauth_version' => '1.0');

        $base_info = $this->build_base_string($url, 'GET', $oauth);
        $composite_key = rawurlencode($this->consumer_secret) . '&' . rawurlencode($this->access_token_secret);
        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        // Make Requests
        $header = array($this->build_authorization_header($oauth), 'Expect:');
        $options_buf = wp_remote_get($url, array(
            'headers' => implode("\n", $header),
            'sslverify' => false,
        ));

        if (!is_wp_error($options_buf) && isset($options_buf['body'])) {
            return json_decode($options_buf['body']);
        } else {
            return false;
        }
    }

    /**
    * adds a link around any entities in a twitter feed
    * twitter entities include urls, user mentions, hashtags and media
    * http://stackoverflow.com/a/15390225
    *
    * @author     mcrumley
    * @param      object $tweet a JSON tweet object v1.1 API
    * @return     string tweet
    */
    public function add_tweet_entity_links($tweet) {
        $text = $tweet->text;
        $entities = isset($tweet->entities) ? $tweet->entities : array();

        $replacements = array();
        if (isset($entities->hashtags)) {
            foreach ($entities->hashtags as $hashtag) {
                list ($start, $end) = $hashtag->indices;
                $replacements[$start] = array($start, $end,
                    "<a href=\"https://twitter.com/search?q={$hashtag->text}\">#{$hashtag->text}</a>");
            }
        }
        if (isset($entities->urls)) {
            foreach ($entities->urls as $url) {
                list ($start, $end) = $url->indices;
                // you can also use $url['expanded_url'] in place of $url['url']
                $replacements[$start] = array($start, $end,
                    "<a href=\"{$url->url}\">{$url->display_url}</a>");
            }
        }
        if (isset($entities->user_mentions)) {
            foreach ($entities->user_mentions as $mention) {
                list ($start, $end) = $mention->indices;
                $replacements[$start] = array($start, $end,
                    "<a href=\"https://twitter.com/{$mention->screen_name}\">@{$mention->screen_name}</a>");
            }
        }
        if (isset($entities->media)) {
            foreach ($entities->media as $media) {
                list ($start, $end) = $media->indices;
                $replacements[$start] = array($start, $end,
                    "<a href=\"{$media->url}\">{$media->display_url}</a>");
            }
        }

        // sort in reverse order by start location
        krsort($replacements);

        foreach ($replacements as $replace_data) {
            list ($start, $end, $replace_text) = $replace_data;
            $text = mb_substr($text, 0, $start, 'UTF-8') . $replace_text . mb_substr($text, $end, NULL, 'UTF-8');
        }

        return $text;
    }

    private function build_base_string ($baseURI, $method, $params) {
        $r = array();
        ksort($params);
        foreach($params as $key=>$value){
            $r[] = "$key=" . rawurlencode($value);
        }
        return $method."&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $r));
    }

    private function build_authorization_header ($oauth) {
        $r = 'Authorization: OAuth ';
        $values = array();
        foreach($oauth as $key=>$value)
        $values[] = "$key=\"" . rawurlencode($value) . "\"";
        $r .= implode(', ', $values);
        return $r;
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
