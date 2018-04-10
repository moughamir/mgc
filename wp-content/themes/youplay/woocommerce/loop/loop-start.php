<?php
/**
 * Product Loop Start
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

$shop_style = yp_opts('shop_style')=='row'?'row':'grid';
?>
<div class="clearfix"></div>
<div class="isotope"><div class="<?php echo yp_sanitize_class('isotope-list ' . $shop_style); ?>">