<?php
/**
 * FIXME: Edit Title Content
 *
 * FIXME: Edit Description Content
 *
 * Please do not edit this file. This file is part of the Cyber Chimps Framework and all modifications
 * should be made in a child theme.
 * FIXME: POINT USERS TO DOWNLOAD OUR STARTER CHILD THEME AND DOCUMENTATION
 *
 * @category Cyber Chimps Framework
 * @package  Framework
 * @since    1.0
 * @author   CyberChimps
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     http://www.cyberchimps.com/
 */

function cyberchimps_init_portfolio_post_type() {
	register_post_type( 'portfolio_images',
		array(
			'labels' => array(
				'name' => __('Portfolio', 'cyberchimps'),
				'singular_name' => __('Images', 'cyberchimps'),
			),
			'public' => true,
			'show_ui' => true, 
			'supports' => array('custom-fields', 'title'),
			'taxonomies' => array( 'portfolio_categories'),
			'has_archive' => true,
			'menu_icon' => get_template_directory_uri() . '/core/lib/images/custom-types/portfolio.png',
			'rewrite' => array('slug' => 'portfolio_images')
		)
	);
	
	$meta_boxes = array();
	
	$mb = new Chimps_Metabox('Portfolio', 'Portfolio Element', array('pages' => array('portfolio_images')));
	$mb
		->tab("Portfolio Element")
			->single_image('portfolio_image', 'Portfolio Image', '')
			->checkbox('custom_portfolio_url_toggle', 'Custom Portfolio URL', '', array('std' => 'off'))
			->text('custom_portfolio_url', 'URL', '')
		->end();
		
	foreach ($meta_boxes as $meta_box) {
		$my_box = new RW_Meta_Box_Taxonomy($meta_box);
	}
}
add_action( 'init', 'cyberchimps_init_portfolio_post_type' );