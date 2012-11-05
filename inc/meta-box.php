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

add_action('admin_head', 'cyberchimps_load_meta_boxes_scripts');
function cyberchimps_load_meta_boxes_scripts() {
	global $post_type;

	//TODO HS Will need to add more post types as they are created
	if ( $post_type == 'page' ) :
		wp_enqueue_style( 'meta-boxes-css', get_template_directory_uri().'/cyberchimps/lib/css/metabox-tabs.css' );
	
		// Enqueue only if it is not done before
		if( !wp_script_is('jf-metabox-tabs') ) :
			wp_enqueue_script('meta-boxes-js', get_template_directory_uri().'/cyberchimps/lib/js/metabox-tabs.js', array('jquery'));	
		endif;	
	endif;
}

add_action('init', 'cyberchimps_init_meta_boxes');
function cyberchimps_init_meta_boxes() {
	
	// Declare variables
	$portfolio_options = array(); 
	$carousel_options = array();
	$slider_options = array();
	$blog_options = array();
	
	// Call taxonomies for select options
	$portfolio_terms = get_terms('portfolio_cats', 'hide_empty=0');
	if( ! is_wp_error( $portfolio_terms ) ):
	foreach($portfolio_terms as $term) {
		$portfolio_options[$term->slug] = $term->name;
	}
	endif;
	
	$carousel_terms = get_terms('carousel_categories', 'hide_empty=0');
	if( ! is_wp_error( $carousel_terms ) ): 
	foreach($carousel_terms as $term) {
		$carousel_options[$term->slug] = $term->name;
	}
	endif;
	
	$slide_terms = get_terms('slide_categories', 'hide_empty=0');
	if( ! is_wp_error( $slide_terms ) ):
	foreach($slide_terms as $term) {
		$slider_options[$term->slug] = $term->name;
	}
	endif;
	
	$category_terms = get_terms('category', 'hide_empty=0');
	if( ! is_wp_error( $category_terms ) ):
	$blog_options['all'] = "All";
	foreach($category_terms as $term) {
		$blog_options[$term->slug] = $term->name;
	}
	endif;
	
	// get cat ids for portfolio
	$cat_terms = get_terms('category', 'hide_empty=0');
	if( ! is_wp_error( $cat_terms ) ):
	$blog_id_options['all'] = "All";
	foreach($cat_terms as $term) {
		$blog_id_options[$term->term_id] = $term->name;
	}
	endif;
	
	// End taxonomy call
	
	$meta_boxes = array();
		
	$mb = new Chimps_Metabox('post_slide_options', __('Slider Options', 'cyberchimps'), array('pages' => array('post')));
	$mb
		->tab("Slider Options")
			->single_image('cyberchimps_slider_image', __('Slider Image', 'cyberchimps'), '')
			->text('cyberchimps_slider_text', __('Slider Text', 'cyberchimps'), __('Enter your slider text here', 'cyberchimps'))			
			->checkbox('slider_hidetitle', __('Title Bar', 'cyberchimps'), '', array('std' => '1'))
			->single_image('slider_custom_thumb', __('Custom Thumbnail', 'cyberchimps'), __('Use the image uploader to upload a custom navigation thumbnail', 'cyberchimps'))
			->sliderhelp('', __('Need Help?', 'cyberchimps'), '')
		->end();

	$mb = new Chimps_Metabox('pages', 'Page Options', array('pages' => array('page')));
	$mb
		->tab("Page Options")
			->image_select('cyberchimps_page_sidebar', 'Select Page Layout', '',  array('options' => array(
				'right_sidebar' => get_template_directory_uri() . '/cyberchimps/lib/images/right.png',
				'left_right_sidebar' => get_template_directory_uri() . '/cyberchimps/lib/images/tworight.png',
				'content_middle' => get_template_directory_uri() . '/cyberchimps/lib/images/rightleft.png',
				'full_width' => get_template_directory_uri() . '/cyberchimps/lib/images/none.png',
				'left_sidebar' => get_template_directory_uri() . '/cyberchimps/lib/images/left.png')
			, 'std' => 'right_sidebar') )
			->checkbox('cyberchimps_page_title_toggle', __('Page Title', 'cyberchimps'), '', array('std' => '1'))
			->section_order('cyberchimps_page_section_order', 'Page Elements', '', array(					
				'options' => apply_filters( 'cyberchimps_elements_draganddrop_page_options', array(
					'boxes'				 => 'Boxes',					
					'page_section'		 => 'Page',
					'portfolio_lite'	 => 'Portfolio Lite',
					'slider_lite'		 => 'Slider Lite',
					'twitterbar_section' => 'Twitter Bar'
				) ),
					'std' => array( 'page_section' )
				))
			->pagehelp('', 'Need Help?', '')
		->tab("Magazine Layout Options")
			->checkbox('cyberchimps_magazine_meta_data_toggle', 'Meta Data', '', array('std' => '1'))
			->select('cyberchimps_magazine_no_of_columns', 'Number of Columns', '', array('options' => array('2', '3')) )
			->select('cyberchimps_magazine_no_of_rows', 'Number of Rows', '', array('options' => array('1', '2', '3', '4')) )
			->checkbox('cyberchimps_magazine_wide_post_toggle', 'Wide Posts Below Magazine', '', array('std' => '1'))
			->select('cyberchimps_magazine_no_of_wide_posts', 'Number of Wide Posts ', '',
						array('options' => array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14',
												 '15', '16', '17', '18', '19', '20')))
		/*->tab("Featured Posts Options")
			->select('cyberchimps_featured_post_category_toggle', 'Select post source', '', array('options' => array('Latest posts', 'From category')) )
			->text('cyberchimps_featured_post_category', 'Enter category', '', array('std' => 'featured'))*/
		->tab("Slider Lite Options")
			->single_image('cyberchimps_slider_lite_slide_one_image', 'Slide One Image', '', array('std' =>  get_template_directory_uri() . '/elements/lib/images/slider/slide1.jpg'))
			->text('cyberchimps_slider_lite_slide_one_url', 'Slide One Link', '', array('std' => 'http://wordpress.org'))
			->single_image('cyberchimps_slider_lite_slide_two_image', 'Slide Two Image', '', array('std' =>  get_template_directory_uri() . '/elements/lib/images/slider/slide2.jpg'))
			->text('cyberchimps_slider_lite_slide_two_url', 'Slide Two Link', '', array('std' => 'http://wordpress.org'))
			->single_image('cyberchimps_slider_lite_slide_three_image', 'Slide Three Image', '', array('std' =>  get_template_directory_uri() . '/elements/lib/images/slider/slide3.jpg'))
			->text('cyberchimps_slider_lite_slide_three_url', 'Slide Three Link', '', array('std' => 'http://wordpress.org'))
		->tab("iFeature Slider Options")
			->select('cyberchimps_slider_size', 'Slider Size', '', array( 'options' => array('full' => 'Full', 'half' => 'Half') ) )
			->select('cyberchimps_slider_type', 'Slider Type', '', array( 'options' => array( 'custom_slides' => 'Custom', 'post' => 'Posts') ) )
			->select('cyberchimps_slider_post_categories', 'Post Categories', '', array( 'options' => $blog_id_options, 'All') )
			->select('cyberchimps_slider_custom_categories', 'Custom Categories', '', array( 'options' => $slider_options, 'All') )
			->text('cyberchimps_number_featured_posts', 'Number of Featured Posts', '', array('default' => 5) )
			->text('cyberchimps_slider_height', 'Slider Height', '' )
			->checkbox('cyberchimps_slider_arrows', 'Slider Arrows', '', array('std' => "1") )
			->sliderhelp('', 'Need Help?', '')
		->tab("Product Options")
			->select('cyberchimps_product_text_align', 'Text Align', '', array('options' => array('Left', 'Right')) )
			->text('cyberchimps_product_title', 'Product Title', '', array('std' => 'Product'))
			->textarea('cyberchimps_product_text', 'Product Text', '', array('std' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. '))
			->select('cyberchimps_product_type', 'Media Type', '', array('options' => array('Image', 'Video')) )
			->single_image('cyberchimps_product_image', 'Product Image', '', array('std' =>  get_template_directory_uri() . '/images/pro/product.jpg'))
			->textarea('cyberchimps_product_video', 'Video Embed', '')
			->checkbox('cyberchimps_product_link_toggle', 'Product Link', '', array('std' => '1'))
			->text('cyberchimps_product_link_url', 'Link URL', '', array('std' => home_url()))
			->text('cyberchimps_product_link_text', 'Link Text', '', array('std' => 'Buy Now'))
		->tab("Callout Options")
			->text('callout_title', 'Callout Title', '',
				array('std' => sprintf( __( '%1$s\'s Call Out Element', 'cyberchimps' ),
					apply_filters( 'cyberchimps_current_theme_name', 'Cyberchimps' ) ) ) )
			->textarea('callout_text', 'Callout Text', '',
				array('std' => sprintf( __( 'Use %1$s\'s Call Out section on any page where you want to deliver an important message to your customer or client.', 'cyberchimps' ),
					apply_filters( 'cyberchimps_current_theme_name', 'Cyberchimps' ) ) ) )
			->checkbox('disable_callout_button', 'Callout Button', '', array('std' => '1'))
			->text('callout_button_text', 'Callout Button Text', '')
			->text('callout_url', 'Callout Button URL', '')
			->checkbox('extra_callout_options', 'Custom Callout Options', '', array('std' => '0'))
			->single_image('callout_image', 'Custom Button Image', '')
			->color('custom_callout_color', 'Custom Background Color', '')
			->color('custom_callout_title_color', 'Custom Title Color', '')
			->color('custom_callout_text_color', 'Custom Text Color', '')
			->color('custom_callout_button_color', 'Custom Button Color', '')
			->color('custom_callout_button_text_color', 'Custom Button Text Color', '')
			->pagehelp('', 'Need help?', '')
		->tab("HTML Box Options")
			->textarea('html_box', __( 'Custom HTML', 'cyberchimps' ), __( 'Enter your custom html here', 'cyberchimps' ) )
		->tab("Portfolio Options")
			->select('portfolio_row_number', 'Images per row', '', array('options' => array( 2 => 'Two', 3 => 'Three', 4 => 'Four'), 'std' => 3) )
			->select('portfolio_category', 'Portfolio Category', '', array('options' => $portfolio_options) )
			->checkbox('portfolio_title_toggle', 'Portfolio Title', '')
			->text('portfolio_title', 'Title', '', array('std' => 'Portfolio'))
		->tab("Portfolio Lite Options")
			->single_image('cyberchimps_portfolio_lite_image_one', 'First Portfolio Image', '', array('std' =>  get_template_directory_uri() . '/cyberchimps/lib/images/portfolio.jpg'))
			->text('cyberchimps_portfolio_lite_image_one_caption', 'First Portfolio Image Caption', '', array('std' => 'Image 1'))
			->checkbox('cyberchimps_portfolio_link_toggle_one', 'First Porfolio Link', '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_one', 'Link URL', '', array('std' => home_url()))
			->single_image('cyberchimps_portfolio_lite_image_two', 'Second Portfolio Image', '', array('std' =>  get_template_directory_uri() . '/cyberchimps/lib/images/portfolio.jpg'))
			->text('cyberchimps_portfolio_lite_image_two_caption', 'Second Portfolio Image Caption', '', array('std' => 'Image 2'))
			->checkbox('cyberchimps_portfolio_link_toggle_two', 'Second Porfolio Link', '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_two', 'Link URL', '', array('std' => home_url()))
			->single_image('cyberchimps_portfolio_lite_image_three', 'Third Portfolio Image', '', array('std' =>  get_template_directory_uri() . '/cyberchimps/lib/images/portfolio.jpg'))
			->text('cyberchimps_portfolio_lite_image_three_caption', 'Third Portfolio Image Caption', '', array('std' => 'Image 3'))
			->checkbox('cyberchimps_portfolio_link_toggle_three', 'Third Porfolio Link', '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_three', 'Link URL', '', array('std' => home_url()))
			->single_image('cyberchimps_portfolio_lite_image_four', 'Fourth Portfolio Image', '', array('std' =>  get_template_directory_uri() . '/cyberchimps/lib/images/portfolio.jpg'))
			->text('cyberchimps_portfolio_lite_image_four_caption', 'Fourth Portfolio Image Caption', '', array('std' => 'Image 4'))
			->checkbox('cyberchimps_portfolio_link_toggle_four', 'Fourth Porfolio Link', '', array('std' => '1'))
			->text('cyberchimps_portfolio_link_url_four', 'Link URL', '', array('std' => home_url()))
			->checkbox('cyberchimps_portfolio_title_toggle', 'Portfolio Title', '')
			->text('cyberchimps_portfolio_title', 'Title', '', array('std' => 'Portfolio'))
		->tab("Recent Posts Options")
			->checkbox('cyberchimps_recent_posts_title_toggle', 'Title', '')
			->text('cyberchimps_recent_posts_title', '', '')
			->select('cyberchimps_recent_posts_category', 'Post Category', '', array('options' => $blog_options, 'all') )
			->checkbox('cyberchimps_recent_posts_images_toggle', 'Images', '')
		->tab("Carousel Options")
			->select('carousel_category', 'Carousel Category', '', array('options' => $carousel_options) )
		->tab("Twitter Options")
			->text('cyberchimps_twitter_handle', 'Twitter Handle', 'Enter your Twitter handle if using the Twitter bar')
		->end();

	foreach ($meta_boxes as $meta_box) {
		$my_box = new RW_Meta_Box_Taxonomy($meta_box);
	}
}