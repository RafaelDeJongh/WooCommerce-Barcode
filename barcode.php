<?php
/*
Plugin Name: WooCommerce Barcode
Plugin URI: https://www.rafaeldejongh.com
Description: A plugin that adds a barcode field to WooCommerce Products
Author: Rafael De Jongh
Version: 1.0
Author URI: https://www.rafaeldejongh.com
*/
//Add barcode to the product inventory tab
add_action('woocommerce_product_options_sku','add_barcode',10,0);
function add_barcode(){
	global $woocommerce,$post;
	$product_barcode = get_post_meta( $post->ID,'_barcode',true);
	if(!$product_barcode) $product_barcode = '';
	woocommerce_wp_text_input(
		array(
			'id'          => '_barcode',
			'label'       => __('Barcode','woocommerce'),
			'placeholder' => 'Scan Barcode',
			'desc_tip'    => 'true',
			'description' => __('Scan barcode.','woocommerce'),
			'value'       => $product_barcode
		)
	);
}
//Save Barcode Field
add_action('woocommerce_process_product_meta','save_barcode',10,1);
function save_barcode($post_id){if(!empty($_POST['_barcode'])) update_post_meta($post_id,'_barcode',sanitize_text_field($_POST['_barcode']));}
//Add Variation Barcode
add_action('woocommerce_product_after_variable_attributes','add_barcode_variations',10,3);
function add_barcode_variations($loop,$variation_data,$variation){
	$variation_barcode = get_post_meta($variation->ID,"_barcode",true);
	if(!$variation_barcode ) $variation_barcode = "";
	woocommerce_wp_text_input(
		array(
			'id'          => '_barcode_' . $loop,
			'label'       => __('Variation Barcode','woocommerce'),
			'placeholder' => 'Scan Barcode',
			'desc_tip'    => 'true',
			'description' => __('Scan barcode.','woocommerce'),
			'value'       => $variation_barcode
		)
	);
}
//Save Variation Barcode
add_action( 'woocommerce_save_product_variation','save_barcode_variations',10,2);
function save_barcode_variations($variation_id,$key){
	$barcode = $_POST["_barcode_$key"];
	if(!empty($barcode)) update_post_meta($variation_id,'_barcode',sanitize_text_field($barcode));
}
//Set POS Custom Code
add_filter('woocommerce_pos_barcode_meta_key','pos_barcode_field');
function pos_barcode_field(){return '_barcode';}
