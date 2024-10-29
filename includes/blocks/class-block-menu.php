<?php
/**
 * The menu block class.
*
* @since	  5.0.0
* @package	awesome-blocks
* @subpackage awesome-blocks/includes/blocks
* @author	 zintaThemes <>
*/

if (! defined( 'ABSPATH' ) || class_exists( 'AwesomeBlocksMenu' )) return;

class AwesomeBlocksMenu extends AwesomeBlocks
{

	/**
	 * The single instance of AwesomeBlocks.
	 * @var	 object
	 * @access  private
	 * @since	 3.0.0
	 */
	private static $_instance = false;

	/**
	 * Define the pagecontent block class.
	 *
	 * @access		public
	 * @since		1.0.0
	 * @var		  unown
	 */
	public function __construct() {
		if ( self::$_instance ) return self::$_instance;
		add_action( 'admin_enqueue_scripts', array($this, 'menu_ajax') );
		$this->register_menu();
		return self::$_instance = true;
	} // End __construct()

	/**
	 * menu_ajax
	 * 
	 * @access		public
	 * @since		1.0.0
	 * @var		  unown
	 */
	public function menu_ajax() {
		$menu_list = get_terms ( 'nav_menu', [ 'hide_empty' => true, 'fields' => 'all' ] );
		! empty($menu_list) && wp_localize_script( 'awesome-blocks', 'awesomeblocks_menu_list', $menu_list );
	}

	/**
	 * pagecontent block
	 */
	private function register_menu() {
		register_block_type( 'awesome-blocks/menu',
			[
				'render_callback' => [
					$this,
					'render_callback'
				],
				'attributes' => [
					'menu' => [
						'type' => 'string',
						'default' => ''
					],
					'menuIcon' => [
						'type' => 'string',
						'default' => ''
					],
					'align' => [
						'type' => 'string',
						'default' => ''
					],
					'editor' => [
						'type' => 'boolean',
						'default' => false
					],
					'id' => [
						'type' => 'string',
						'default' => ''
					],
					'container' => [
						'type' => 'boolean',
						'default' => false
					],
					'classesList' => [
						'type' => 'string',
						'default' => ''
					]
				]
			]
		);
	}

	/**
	 * render_callback
	 *
	 * @param	  array   $attributes  The attributes
	 *
	 * @return	 string  ( description_of_the_return_value )
	 */
	public function render_callback( $attributes = [] ) {
		if ( empty( $attributes ) || ! is_array($attributes) ) return '';
		extract($attributes);

		// $depth = $editor ? 1 : 4;
		$depth = 4;

		$menu_align = $align ? ' awesome-blocks-menu--' . $align : '';

		$menu_icon = $menuIcon ? '<li class="awesome-blocks-menu-icon"><a href="/"><i class="' . esc_attr($menuIcon) . '"></i></a></li>' : '';

		$class = ! empty($classesList) ? ' '.$classesList : '';

		$menu_container = isset($container) && $container ? ' container' : '';

		$menu_before = '<div class="wp-block-awesome-blocks-menu' . esc_attr($menu_align . $class . ' ' . $id) . '"><div class="awesome-blocks-toggle-menu"><div class="awesome-blocks-toggle-menu__lines"></div></div><ul class="awesome-blocks-menuul' . esc_attr($menu_container) . '">' . $menu_icon;
		$wp_nav_menu = wp_nav_menu( array(
			'sort_column' => 'menu_order',
			'container' => false,
			'menu' => $menu,
			// 'menu_id' => $id,
			'menu_class' => false,
			'items_wrap' => '%3$s',
			'echo' => false,
			'depth'=> $depth
		) );
		$menu_after = '</ul></div>';
		return $wp_nav_menu ? $menu_before . $wp_nav_menu . $menu_after : '';
	} // End render_callback()

} // End AwesomeBlocks {}
