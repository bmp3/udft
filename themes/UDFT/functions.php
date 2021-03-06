<?php
/*
 *  Author: CA
 *  Custom functions, support, custom post types and more.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

setlocale(LC_ALL, 'ru_RU', 'ru_RU.UTF-8', 'ru', 'russian');

require_once ( 'inc/redux-config.php' );


if (function_exists('add_theme_support'))
{

    add_theme_support('menus');
    add_theme_support('widgets');

    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    add_theme_support('automatic-feed-links');

    load_theme_textdomain('tpl', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/


add_action( 'init', 'udft::init' );
add_action( 'admin_init', 'udft::admin_init' );

class UDFT {

	static function init() {

		global $udft;

		$udft['fields'] = array(
			'width-layout',
			'sidebar-layout',
			'header-type',
			'header-bg',
			'header-slider'
		);

		add_filter( 'widget_text', 'do_shortcode' );

		if ( ! is_admin() ) {

			wp_enqueue_style( 'template_css', get_template_directory_uri() . '/css/template.css' );
			wp_enqueue_style( 'bootstrap-template_css', get_template_directory_uri() . '/css/bootstrap-grid.css' );
			wp_enqueue_style( 'main_custom_css', get_template_directory_uri() . '/css/main_style.css' );
			wp_enqueue_style( 'fontawesome_css', get_template_directory_uri() . '/css/font-awesome.css' );
            wp_enqueue_style( 'slick_css', get_template_directory_uri() . '/inc/slick/slick.css' );
            wp_enqueue_style( 'slick__theme_css', get_template_directory_uri() . '/inc/slick/slick-theme.css' );

            wp_register_script( 'slick_js', get_template_directory_uri() . '/inc/slick/slick.js', array( 'jquery' ), false, true );
            wp_enqueue_script( 'slick_js' );
			wp_register_script( 'custom_js', get_template_directory_uri() . '/js/custom.js', array( 'jquery' ), false, true );
			wp_enqueue_script( 'custom_js' );

		} else {
			wp_enqueue_style( 'kmwp_admin_css', get_stylesheet_directory_uri() . '/css/admin/admin.css' );
			wp_enqueue_script( 'jquery-ui-core', array( 'jquery' ) );
			wp_enqueue_script( 'kmwp_admin_js', get_stylesheet_directory_uri() . '/js/admin/admin.js', array(
				'jquery',
				'jquery-ui-core'
			) );
		}

		register_nav_menus( array(
			'header-location' => 'Top Memu',
			'footer-location' => 'Footer Menu'
		) );

		add_filter( 'body_class', 'udft::custom_class_names' );

        self::set_custom_post_types();

	}


	static function custom_class_names( $classes ) {

		global $post;

		if ( is_page() ) {
			$classes[] = 'customized-page';
			if ( $post && is_int( $post->ID ) ) {
				$classes[] = 'page-' . strtolower( preg_replace( array( '/\s/', '/,|\.|\"|\'/' ), array(
						'-',
						''
					), $post->post_title ) );
			}
		}

		if ( $post && $post->post_type && $post->post_type == 'post' ) {
			$classes[] = 'customized-post';
		}

		return $classes;

	}


	static function start_content_layout( $echo = true ) {

		global $post, $udft;

		if ( $udft['width-layout'] == 1 ) $udft['box-type'] = ' container-fluid ';
		else $udft['box-type'] = ' container ';

		if ( $udft['sidebar-layout'] == 1 ) {
			$out = '<div class="content-box col col-lg-12">';
		} else if ( $udft['sidebar-layout'] == 2 ) {
			$out =
				'<div class="left-sidebar-box col col-lg-3 col-sm-12">' .
				udft::get_sidebar_content( 'left-sidebar' ) .
				'</div>
            <div class="content-box col col-lg-6 col-sm-12">';
		} else if ( $udft['sidebar-layout'] == 3 ) {
			$out =
				'<div class="left-sidebar-box col col-lg-4 col-sm-12">' .
				udft::get_sidebar_content( 'left-sidebar' ) .
				'</div>
             <div class="content-box col col-lg-8 col-sm-12">';
		}
		if ( $udft['sidebar-layout'] == 4 ) {
			$out =
				'<div class="content-box col col-lg-8 col-sm-12">';
		}

		if ( $echo ) {
			echo $out;
		} else {
			return $out;
		}

	}


	static function finish_content_layout( $echo = true ) {

		global $post, $udft;

		if ( $udft['sidebar-layout'] == 1 ) {
			$out = '</div>';
		} else if ( $udft['sidebar-layout'] == 2 ) {
			$out = '</div>
                <div class="right-sidebar-box col col-lg-3 col-sm-12">' .
			       udft::get_sidebar_content( 'right-sidebar' ) .
			       '</div>';
		} else if ( $udft['sidebar-layout'] == 3 ) {
			$out =
				'</div>';
		}
		if ( $udft['sidebar-layout'] == 4 ) {
			$out = '</div>
                <div class="right-sidebar-box col col-lg-4 col-sm-12">' .
			       udft::get_sidebar_content( 'right-sidebar' ) .
			       '</div>';
		}

		if ( $echo ) {
			echo $out;
		} else {
			return $out;
		}

	}


	static function get_sidebar_content( $sidebar_id, $mode = 'return' ) {

		ob_start();
		dynamic_sidebar( $sidebar_id );
		$out = ob_get_contents();
		ob_end_clean();

		return $out;

	}

	static function get_template_part( $template, $part_name = null, $mode = 'return' ) {

		if ( $mode == 'return' ) {
			ob_start();
			get_template_part( $template, $part_name );
			$out = ob_get_contents();
			ob_end_clean();

			return $out;
		} else {
			get_template_part( $template );
		}

	}


	static function correct_global_var( $global = null, $meta = null, $p = null ) {

		global $post, $udft;

        if ( ! $global ) {
            $global = $udft;
        }

		if ( !$p ) {
			if ( $post ) {
				$p = $post;
			}
			else {
				return $global;
			}
		}

		if ( $p && $p->ID ) {
			if ( !$meta ) {
				$meta = json_decode( get_post_meta( $p->ID, 'post-settings', true ), true );
			}
			if ( !$meta || $meta == '' ) {
                return $global;
            }
            else {
				foreach( $udft['fields'] as $i => $name ) {
					if ( isset( $meta[$name] ) ) {
						if ( $name == 'header-bg' ) {
							$meta[$name] = array( 'id' => $meta[$name], 'url' => wp_get_attachment_image_src( $meta[$name], 'full' )[0] );
						}
						$global[$name] = $meta[$name];
					}
				}
			}

		}

		return $global;

	}


	/* BackEnd Methods */

	static function admin_init() {

		add_action( 'admin_enqueue_scripts', 'udft::get_admin_scripts', 99 );
		add_action( 'add_meta_boxes', 'udft::get_metabox' );
		add_action( 'wp_ajax_save_post_settings', 'udft::save_post_settings' );
		add_action( 'save_post', 'udft::save_post_settings' );

	}

	static function get_admin_scripts() {

		//wp_enqueue_style( 'kmwp_admin_bootstrap', get_stylesheet_directory_uri() . '/css/template.css' );
		//wp_enqueue_style( 'kmwp_admin_bootstrap', get_stylesheet_directory_uri() . '/css/bootstrap-system.css' );
		//wp_enqueue_style( 'kmwp_admin_font-awesome', get_stylesheet_directory_uri() . '/css/font-awesome.css' );
		wp_enqueue_style( 'kmwp_admin_css', get_stylesheet_directory_uri() . '/css/admin/admin.css' );
		wp_enqueue_script( 'jquery-ui-core', array('jquery'));
		wp_enqueue_script( 'kmwp_admin_js', get_stylesheet_directory_uri() .  '/js/admin/admin.js', array('jquery', 'jquery-ui-core') );

	}

	static function get_metabox() {

		global $post;

		add_meta_box( __( 'Settings', '' ), __( 'Settings', '' ), 'udft::post_metabox_process', array ( 'post', 'page' ), 'normal', 'low' );

	}


	static function post_metabox_process() {

		global $post, $udft;

        $meta = udft::correct_global_var();

		$out =
			'<div class="udft-post-settings">
            <div class="reset-box">
            <button class="btn reset-post-settings">reset to global</button>
            <input class="ps-reset" name="reset-ps" value="0">
            <input class="ps-changed" name="ps-changed" value="0">
            </div>
            <div class="post-settings tabs-box">
                <div class="tab-nav row">
                    <div class="tab-item col"><a class="tab-a" data-trigger="layout">layout</a></div>
                    <div class="tab-item col"><a class="tab-a" data-trigger="header">header</a></div>
                    <div class="tab-item col"><a class="tab-a" data-trigger="content">content</a></div>
                    <div class="tab-item col"><a class="tab-a" data-trigger="footer">footer</a></div>
                </div> 
                <div class="tab-contents">
                    <div class="tab-content-item" data-target="layout">
                        <div class="tab-item-box">
                            <div class="width-layout img-controls-box row">
                                <div class="control-title col">Choose page layout</div>
                                <div class="control-values col">
                                    <div class="control-value img-control" data-value="1"><img src="' . get_template_directory_uri() . '/img/admin/full.gif' . '"></div>
                                    <div class="control-value img-control" data-value="2"><img src="' . get_template_directory_uri() . '/img/admin/boxed.gif' . '"></div> 
                                </div> 
                                <input class="ps-input img-control-receiver" name="ps[width-layout]" value="' . $meta['width-layout']. '">                           
                            </div>
                            <div class="sidebar-layout img-controls-box row">
                                <div class="control-title col">Choose sidebar layout</div>
                                <div class="control-values col">
                                    <div class="control-value img-control" data-value="1"><img src="' . get_template_directory_uri() . '/img/admin/nosidebar.gif' . '"></div>
                                    <div class="control-value img-control" data-value="2"><img src="' . get_template_directory_uri() . '/img/admin/2sidebars.gif' . '"></div>
                                    <div class="control-value img-control" data-value="3"><img src="' . get_template_directory_uri() . '/img/admin/leftsidebar.gif' . '"></div>
                                    <div class="control-value img-control" data-value="4"><img src="' . get_template_directory_uri() . '/img/admin/rightsidebar.gif' . '"></div>   
                                </div> 
                                <input class="ps-input img-control-receiver" name="ps[sidebar-layout]" value="' . $meta['sidebar-layout']. '">                           
                            </div>                            
                        </div>
                    </div>
                    <div class="tab-content-item" data-target="header">
                        <div class="tab-item-box">
                            <div class="tab-select-box header-content">
                                <select class="tab-select">
                                    <option value="' . '1' . '" ' . selected( 1, $meta['header-type'], false ) . ' data-target="1">' . 'background-image' . '</option>
                                    <option value="' . '2' . '" ' . selected( 2, $meta['header-type'], false ) . ' data-target="2">' . 'slider' . '</option>
                                </select>
                                <input class="ps-input header-type-receiver" name="ps[header-type]" value="' . $meta['header-type']. '">
                               <div class="tab-select-values">
                                   <div class="tab-select-item ' . udft::get_active_css_class( 1,  $meta['header-type'] ) . '" data-value="1">
                                   	   <div class="event_control imgs_control">
	                                       <button class="add_img_control">add image select</button>';
	                                       if ( isset( $meta['header-bg'] ) && is_int(  $meta['header-bg'] )) {
                                               foreach ($set['images'] as $id) {
                                                   $out .= udft::set_img_control( $id, 'ps[header-bg]' );
                                               }
                                           }
	                                       else {
	                                       	   $id = isset( $meta['header-bg']['id'] ) ? $meta['header-bg']['id'] : null;
		                                       $out .= udft::set_img_control( $id, 'ps[header-bg]' );
	                                       }
	                                   $out .= '</div>                                       
                                   </div>
                                   <div class="tab-select-item ' . udft::get_active_css_class( 2,  $meta['header-type'] ) . '" data-value="2">' .
                                       '' .
                                   '</div>
                               </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-content-item" data-target="content">
                        <div class="tab-item-box">
                        
                        </div>
                    </div>
                    <div class="tab-content-item" data-target="footer">
                        <div class="tab-item-box">
                        
                        </div>
                    </div>                                      
                </div>   
           </div> 
        </div>';

		echo $out;

	}


    static function set_img_control ( $id = null, $name ) {

        $out = '<div class="img_control">';
        if ( $id ) { $bg = 'style="background-image:url(' . wp_get_attachment_image_src( $id, 'full' )[0] . ');"'; $val = $id; }
        else { $val = ''; $bg = ''; }
        $out .= '<div class="img_prv" ' . $bg . '></div>';
        $out .= '<input type="hidden" value="' . $val . '" name=' . $name . '>';
        $out .= '<button class="img_change">change image</button>';
        $out .= '<button class="img_remove">remove image</button>';
        $out .= '</div>';

        return $out;

    }


    static function get_active_css_class( $base, $var ) {
		if ( $var == $base ) return 'active';
		return '';
	}


	static function save_post_settings(){

		global $post, $udft;

		$has_self_data = false;

		foreach ( $udft['fields'] as $i => $field ) {
			if ( isset( $_POST['ps'][$field] ) && isset( $udft[$field] ) ) {
				if ( !is_array( $udft[$field] ) ) {
					if ( $_POST['ps'][$field] != $udft[$field] ) {
						$has_self_data = true;
					}
				}
				else if ( is_array( $udft[$field] ) ) {
                    if ( $udft[$field]['id'] != $_POST['ps'][$field] ) {
	                    $has_self_data = true;
                    }
				}
			}
		}

		if ( isset( $_POST['ps'] ) && is_array( $_POST['ps'] ) ) {
			if ( $_POST['reset-ps'] == 1 ) {
				delete_post_meta( $post->ID, 'post-settings' );
				return;
			}
			if ( $has_self_data ) {
				$ps = json_encode($_POST['ps']);
				update_post_meta($post->ID, 'post-settings', $ps);
			}
		}

	}


    static function get_excerpt( $p = null, $n = 15 ) {

		global $post;

		if ( !$p ) $p = $post;

        $content = $p->post_content;
        $content = str_replace(']]>', ']]&gt;', $content);
        $content = preg_replace( '/\[[^\]]+\]/U', '', $content );

        $content = explode( ' ', $content );
        $content = implode( ' ', array_slice( $content, 0, $n ) );

        return $content;

    }


    static function set_custom_post_types()
    {

        register_post_type('portfolio', array(

            'label' => 'portfolio',
            'labels' => array(
                'name' => 'Portfolio',
                'singular_name' => __('portfolio', 'tpl'),
                'add_new' => __('Add portfolios', 'tpl'),
                'add_new_item' => __('Adding portfolio', 'tpl'),
                'edit_item' => __('Edit portfolio', 'tpl'),
                'new_item' => __('New portfolio', 'tpl'),
                'view_item' => __('View portfolio', 'tpl'),
                'search_items' => __('Search portfolio', 'tpl'),
                'not_found' => __('Not found portfolios', 'tpl'),
                'not_found_in_trash' => __('Not found portfolios in trash', 'tpl'),
                'parent_item_colon' => '',
                'menu_name' => 'Portfolios',
            ),
            'description' => '',
            'public' => true,
            'publicly_queryable' => true,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => true,
            'show_in_rest' => true,
            'rest_base' => null,
            'menu_position' => 40,
            'hierarchical' => false,
            'supports' => array('title', 'editor', 'excerpt', 'thumbnail'),
            'has_archive' => true,
            //'rewrite' => array( 'slug' => 'portfolio' ),
            'query_var' => true,

        ));

        $labels = array(
            'name' => _x('Portfolio Categories', 'taxonomy general name', 'tpl'),
            'singular_name' => _x('Protfolio', 'taxonomy singular name', 'tpl'),
            'search_items' => __('Search Protfolio Categories', 'tpl'),
            'all_items' => __('All Protfolios', 'tpl'),
            'parent_item' => __('Parent Protfolio', 'tpl'),
            'parent_item_colon' => __('Parent Protfolio:', 'tpl'),
            'edit_item' => __('Edit Protfolio', 'tpl'),
            'update_item' => __('Update Protfolio', 'tpl'),
            'add_new_item' => __('Add New Protfolio category', 'tpl'),
            'new_item_name' => __('New Protfolio Name', 'tpl'),
            'menu_name' => __('Protfolio categories', 'tpl'),
        );

        $args = array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'portfolios'),
        );

        register_taxonomy('portfolios', array('portfolio'), $args);

    }

}

/* CUSTOM */


add_filter('upload_mimes', 'kmwp_mime_types');

function kmwp_mime_types( $mimes ) {

    $mimes['svg'] = 'image/svg+xml';
    return $mimes;

}







?>
