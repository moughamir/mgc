<?php
// Before removing this file, please verify the PHP ini setting `auto_prepend_file` does not point to this.

if (file_exists('/home/ubuntu/workspace/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/home/ubuntu/workspace/wp-content/wflogs/');
	include_once '/home/ubuntu/workspace/wp-content/plugins/wordfence/waf/bootstrap.php';
}
?>