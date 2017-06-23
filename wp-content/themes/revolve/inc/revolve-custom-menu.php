<?php
    /**
     * Add a property to a menu item
     *
     * @param object $item The menu item object.
     */
    function revolve_custom_nav_menu_item( $item ) {
       $item->dispsubsec = get_post_meta( $item->ID, '_menu_item_dispsubsec', true );
       return $item;
    }
    
    add_filter( 'wp_setup_nav_menu_item', 'revolve_custom_nav_menu_item' );
    
    /**
     * Save menu item custom fields' values
     * 
     * @link https://codex.wordpress.org/Function_Reference/sanitize_html_class
     */
    function revolve_custom_update_nav_menu_item( $menu_id, $menu_item_db_id, $menu_item_args ){
        $meta_value = get_post_meta( $menu_item_db_id, '_menu_item_dispsubsec', true );
        
        if(!isset($_POST['menu-item-dispsubsec'][$menu_item_db_id])){
            $new_meta_value = '';
        } else {
            $new_meta_value = $_POST['menu-item-dispsubsec'][$menu_item_db_id];
        }

		if( '' == $new_meta_value ) {
			delete_post_meta( $menu_item_db_id, '_menu_item_dispsubsec', $meta_value );
		}
		elseif( $meta_value !== $new_meta_value ) {
			update_post_meta( $menu_item_db_id, '_menu_item_dispsubsec', $new_meta_value );
		}
    }
    add_action( 'wp_update_nav_menu_item', 'revolve_custom_update_nav_menu_item', 10, 3 );
    
    add_filter( 'wp_edit_nav_menu_walker', function( $class ){ return 'Revolve_Walker_Nav_Menu_Edit'; } );

    /**
     * Create HTML list of nav menu input items.
     *
     * @package WordPress
     * @since 3.0.0
     * @uses Walker_Nav_Menu
     */
    class Revolve_Walker_Nav_Menu_Edit extends Walker_Nav_Menu {
    	/**
    	 * Starts the list before the elements are added.
    	 *
    	 * @see Walker_Nav_Menu::start_lvl()
    	 *
    	 * @since 3.0.0
    	 *
    	 * @param string $output Passed by reference.
    	 * @param int    $depth  Depth of menu item. Used for padding.
    	 * @param array  $args   Not used.
    	 */
    	public function start_lvl( &$output, $depth = 0, $args = array() ) {}
    
    	/**
    	 * Ends the list of after the elements are added.
    	 *
    	 * @see Walker_Nav_Menu::end_lvl()
    	 *
    	 * @since 3.0.0
    	 *
    	 * @param string $output Passed by reference.
    	 * @param int    $depth  Depth of menu item. Used for padding.
    	 * @param array  $args   Not used.
    	 */
    	public function end_lvl( &$output, $depth = 0, $args = array() ) {}
    
    	/**
    	 * Start the element output.
    	 *
    	 * @see Walker_Nav_Menu::start_el()
    	 * @since 3.0.0
    	 *
    	 * @global int $_wp_nav_menu_max_depth
    	 *
    	 * @param string $output Passed by reference. Used to append additional content.
    	 * @param object $item   Menu item data object.
    	 * @param int    $depth  Depth of menu item. Used for padding.
    	 * @param array  $args   Not used.
    	 * @param int    $id     Not used.
    	 */
    	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    		global $_wp_nav_menu_max_depth;
    		$_wp_nav_menu_max_depth = $depth > $_wp_nav_menu_max_depth ? $depth : $_wp_nav_menu_max_depth;
    
    		ob_start();
    		$item_id = esc_attr( $item->ID );
    		$removed_args = array(
    			'action',
    			'customlink-tab',
    			'edit-menu-item',
    			'menu-item',
    			'page-tab',
    			'_wpnonce',
    		);
    
    		$original_title = '';
    		if ( 'taxonomy' == $item->type ) {
    			$original_title = get_term_field( 'name', $item->object_id, $item->object, 'raw' );
    			if ( is_wp_error( $original_title ) )
    				$original_title = false;
    		} elseif ( 'post_type' == $item->type ) {
    			$original_object = get_post( $item->object_id );
    			$original_title = get_the_title( $original_object->ID );
    		}
    
    		$classes = array(
    			'menu-item menu-item-depth-' . $depth,
    			'menu-item-' . esc_attr( $item->object ),
    			'menu-item-edit-' . ( ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? 'active' : 'inactive'),
    		);
    
    		$title = $item->title;
    
    		if ( ! empty( $item->_invalid ) ) {
    			$classes[] = 'menu-item-invalid';
    			/* translators: %s: title of menu item which is invalid */
    			$title = sprintf( __( '%s (Invalid)', 'revolve' ), $item->title );
    		} elseif ( isset( $item->post_status ) && 'draft' == $item->post_status ) {
    			$classes[] = 'pending';
    			/* translators: %s: title of menu item in draft status */
    			$title = sprintf( __('%s (Pending)', 'revolve'), $item->title );
    		}
    
    		$title = ( ! isset( $item->label ) || '' == $item->label ) ? $title : $item->label;
    
    		$submenu_text = '';
    		if ( 0 == $depth )
    			$submenu_text = 'style="display: none;"';
    
    		?>
    		<li id="menu-item-<?php echo $item_id; ?>" class="<?php echo implode(' ', $classes ); ?>">
    			<div class="menu-item-bar">
    				<div class="menu-item-handle">
    					<span class="item-title"><span class="menu-item-title"><?php echo esc_html( $title ); ?></span> <span class="is-submenu" <?php echo $submenu_text; ?>><?php _e( 'sub item', 'revolve' ); ?></span></span>
    					<span class="item-controls">
    						<span class="item-type"><?php echo esc_html( $item->type_label ); ?></span>
    						<span class="item-order hide-if-js">
    							<a href="<?php
    								echo wp_nonce_url(
    									add_query_arg(
    										array(
    											'action' => 'move-up-menu-item',
    											'menu-item' => $item_id,
    										),
    										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
    									),
    									'move-menu_item'
    								);
    							?>" class="item-move-up"><abbr title="<?php esc_attr_e('Move up', 'revolve'); ?>">&#8593;</abbr></a>
    							|
    							<a href="<?php
    								echo wp_nonce_url(
    									add_query_arg(
    										array(
    											'action' => 'move-down-menu-item',
    											'menu-item' => $item_id,
    										),
    										remove_query_arg($removed_args, admin_url( 'nav-menus.php' ) )
    									),
    									'move-menu_item'
    								);
    							?>" class="item-move-down"><abbr title="<?php esc_attr_e('Move down', 'revolve'); ?>">&#8595;</abbr></a>
    						</span>
    						<a class="item-edit" id="edit-<?php echo $item_id; ?>" title="<?php esc_attr_e('Edit Menu Item', 'revolve'); ?>" href="<?php
    							echo ( isset( $_GET['edit-menu-item'] ) && $item_id == $_GET['edit-menu-item'] ) ? admin_url( 'nav-menus.php' ) : add_query_arg( 'edit-menu-item', $item_id, remove_query_arg( $removed_args, admin_url( 'nav-menus.php#menu-item-settings-' . $item_id ) ) );
    						?>"><?php _e( 'Edit Menu Item', 'revolve' ); ?></a>
    					</span>
    				</div>
    			</div>
    
    			<div class="menu-item-settings" id="menu-item-settings-<?php echo $item_id; ?>">
    				<?php if ( 'custom' == $item->type ) : ?>
    					<p class="field-url description description-wide">
    						<label for="edit-menu-item-url-<?php echo $item_id; ?>">
    							<?php _e( 'URL', 'revolve' ); ?><br />
    							<input type="text" id="edit-menu-item-url-<?php echo $item_id; ?>" class="widefat code edit-menu-item-url" name="menu-item-url[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->url ); ?>" />
    						</label>
    					</p>
    				<?php endif; ?>
    				<p class="description description-wide">
    					<label for="edit-menu-item-title-<?php echo $item_id; ?>">
    						<?php _e( 'Navigation Label', 'revolve' ); ?><br />
    						<input type="text" id="edit-menu-item-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-title" name="menu-item-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->title ); ?>" />
    					</label>
    				</p>
    				<p class="field-title-attribute description description-wide">
    					<label for="edit-menu-item-attr-title-<?php echo $item_id; ?>">
    						<?php _e( 'Title Attribute', 'revolve' ); ?><br />
    						<input type="text" id="edit-menu-item-attr-title-<?php echo $item_id; ?>" class="widefat edit-menu-item-attr-title" name="menu-item-attr-title[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->post_excerpt ); ?>" />
    					</label>
    				</p>
    				<p class="field-link-target description">
    					<label for="edit-menu-item-target-<?php echo $item_id; ?>">
    						<input type="checkbox" id="edit-menu-item-target-<?php echo $item_id; ?>" value="_blank" name="menu-item-target[<?php echo $item_id; ?>]"<?php checked( $item->target, '_blank' ); ?> />
    						<?php _e( 'Open link in a new window/tab', 'revolve' ); ?>
    					</label>
    				</p>
    				<p class="field-css-classes description description-thin">
    					<label for="edit-menu-item-classes-<?php echo $item_id; ?>">
    						<?php _e( 'CSS Classes (optional)', 'revolve' ); ?><br />
    						<input type="text" id="edit-menu-item-classes-<?php echo $item_id; ?>" class="widefat code edit-menu-item-classes" name="menu-item-classes[<?php echo $item_id; ?>]" value="<?php echo esc_attr( implode(' ', $item->classes ) ); ?>" />
    					</label>
    				</p>
    				<p class="field-xfn description description-thin">
    					<label for="edit-menu-item-xfn-<?php echo $item_id; ?>">
    						<?php _e( 'Link Relationship (XFN)', 'revolve' ); ?><br />
    						<input type="text" id="edit-menu-item-xfn-<?php echo $item_id; ?>" class="widefat code edit-menu-item-xfn" name="menu-item-xfn[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->xfn ); ?>" />
    					</label>
    				</p>
    				<p class="field-description description description-wide">
    					<label for="edit-menu-item-description-<?php echo $item_id; ?>">
    						<?php _e( 'Description', 'revolve' ); ?><br />
    						<textarea id="edit-menu-item-description-<?php echo $item_id; ?>" class="widefat edit-menu-item-description" rows="3" cols="20" name="menu-item-description[<?php echo $item_id; ?>]"><?php echo esc_html( $item->description ); // textarea_escaped ?></textarea>
    						<span class="description"><?php _e('The description will be displayed in the menu if the current theme supports it.', 'revolve'); ?></span>
    					</label>
    				</p>
    
    				<p class="field-move hide-if-no-js description description-wide">
    					<label>
    						<span><?php _e( 'Move', 'revolve' ); ?></span>
    						<a href="#" class="menus-move menus-move-up" data-dir="up"><?php _e( 'Up one', 'revolve' ); ?></a>
    						<a href="#" class="menus-move menus-move-down" data-dir="down"><?php _e( 'Down one', 'revolve' ); ?></a>
    						<a href="#" class="menus-move menus-move-left" data-dir="left"></a>
    						<a href="#" class="menus-move menus-move-right" data-dir="right"></a>
    						<a href="#" class="menus-move menus-move-top" data-dir="top"><?php _e( 'To the top', 'revolve' ); ?></a>
    					</label>
    				</p>
                    
                    <!-- Added Code -->
                    <p class="field-link-dispsubsec description">
    					<label for="edit-menu-item-dispsubsec-<?php echo $item_id; ?>">
    						<input class="cus-menu-check" type="checkbox" id="edit-menu-item-dispsubsec-<?php echo $item_id; ?>" <?php checked( $item->dispsubsec, 'yes' ); ?> />
    						<?php _e( 'Display Sub pages as Sub-Sections', 'revolve' ); ?>
    					</label>
                        <input name="menu-item-dispsubsec[<?php echo $item_id; ?>]" type="hidden" class="mnu-falback-val" value="<?php echo esc_attr( $item->dispsubsec ); ?>" />
    				</p>
                    <!-- Added Code -->
    
    				<div class="menu-item-actions description-wide submitbox">
    					<?php if ( 'custom' != $item->type && $original_title !== false ) : ?>
    						<p class="link-to-original">
    							<?php printf( __('Original: %s', 'revolve'), '<a href="' . esc_attr( $item->url ) . '">' . esc_html( $original_title ) . '</a>' ); ?>
    						</p>
    					<?php endif; ?>
    					<a class="item-delete submitdelete deletion" id="delete-<?php echo $item_id; ?>" href="<?php
    					echo wp_nonce_url(
    						add_query_arg(
    							array(
    								'action' => 'delete-menu-item',
    								'menu-item' => $item_id,
    							),
    							admin_url( 'nav-menus.php' )
    						),
    						'delete-menu_item_' . $item_id
    					); ?>"><?php _e( 'Remove', 'revolve' ); ?></a> <span class="meta-sep hide-if-no-js"> | </span> <a class="item-cancel submitcancel hide-if-no-js" id="cancel-<?php echo $item_id; ?>" href="<?php echo esc_url( add_query_arg( array( 'edit-menu-item' => $item_id, 'cancel' => time() ), admin_url( 'nav-menus.php' ) ) );
    						?>#menu-item-settings-<?php echo $item_id; ?>"><?php _e('Cancel', 'revolve'); ?></a>
    				</div>
    
    				<input class="menu-item-data-db-id" type="hidden" name="menu-item-db-id[<?php echo $item_id; ?>]" value="<?php echo $item_id; ?>" />
    				<input class="menu-item-data-object-id" type="hidden" name="menu-item-object-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object_id ); ?>" />
    				<input class="menu-item-data-object" type="hidden" name="menu-item-object[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->object ); ?>" />
    				<input class="menu-item-data-parent-id" type="hidden" name="menu-item-parent-id[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_item_parent ); ?>" />
    				<input class="menu-item-data-position" type="hidden" name="menu-item-position[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->menu_order ); ?>" />
    				<input class="menu-item-data-type" type="hidden" name="menu-item-type[<?php echo $item_id; ?>]" value="<?php echo esc_attr( $item->type ); ?>" />
    			</div><!-- .menu-item-settings-->
    			<ul class="menu-item-transport"></ul>
    		<?php
    		$output .= ob_get_clean();
    	}
    
    } // Walker_Nav_Menu_Edit
?>
<?php
    /**
     * Create HTML list of nav menu items.
     *
     * @since 3.0.0
     * @uses Walker
     */
    class Revolve_Walker_Nav_Menu extends Walker_Nav_Menu {
    	/**
    	 * What the class handles.
    	 *
    	 * @see Walker::$tree_type
    	 * @since 3.0.0
    	 * @var string
    	 */
    	public $tree_type = array( 'post_type', 'taxonomy', 'custom' );
    
    	/**
    	 * Database fields to use.
    	 *
    	 * @see Walker::$db_fields
    	 * @since 3.0.0
    	 * @todo Decouple this.
    	 * @var array
    	 */
    	public $db_fields = array( 'parent' => 'menu_item_parent', 'id' => 'db_id' );
    
    	/**
    	 * Starts the list before the elements are added.
    	 *
    	 * @see Walker::start_lvl()
    	 *
    	 * @since 3.0.0
    	 *
    	 * @param string $output Passed by reference. Used to append additional content.
    	 * @param int    $depth  Depth of menu item. Used for padding.
    	 * @param array  $args   An array of arguments. @see wp_nav_menu()
    	 */
    	public function start_lvl( &$output, $depth = 0, $args = array() ) {
    		$indent = str_repeat("\t", $depth);
    		$output .= "\n$indent<ul class=\"sub-menu\">\n";
    	}
    
    	/**
    	 * Ends the list of after the elements are added.
    	 *
    	 * @see Walker::end_lvl()
    	 *
    	 * @since 3.0.0
    	 *
    	 * @param string $output Passed by reference. Used to append additional content.
    	 * @param int    $depth  Depth of menu item. Used for padding.
    	 * @param array  $args   An array of arguments. @see wp_nav_menu()
    	 */
    	public function end_lvl( &$output, $depth = 0, $args = array() ) {
    		$indent = str_repeat("\t", $depth);
    		$output .= "$indent</ul>\n";
    	}
    
    	/**
    	 * Start the element output.
    	 *
    	 * @see Walker::start_el()
    	 *
    	 * @since 3.0.0
    	 *
    	 * @param string $output Passed by reference. Used to append additional content.
    	 * @param object $item   Menu item data object.
    	 * @param int    $depth  Depth of menu item. Used for padding.
    	 * @param array  $args   An array of arguments. @see wp_nav_menu()
    	 * @param int    $id     Current item ID.
    	 */
    	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
    		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    
    		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
    		$classes[] = 'menu-item-' . $item->ID;
            
            if($item->object == 'page') {
                $classes[] = 'page-item-'.$item->object_id;
            }
    
    		/**
    		 * Filter the CSS class(es) applied to a menu item's list item element.
    		 *
    		 * @since 3.0.0
    		 * @since 4.1.0 The `$depth` parameter was added.
    		 *
    		 * @param array  $classes The CSS classes that are applied to the menu item's `<li>` element.
    		 * @param object $item    The current menu item.
    		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
    		 * @param int    $depth   Depth of menu item. Used for padding.
    		 */
    		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
    		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
    
    		/**
    		 * Filter the ID applied to a menu item's list item element.
    		 *
    		 * @since 3.0.1
    		 * @since 4.1.0 The `$depth` parameter was added.
    		 *
    		 * @param string $menu_id The ID that is applied to the menu item's `<li>` element.
    		 * @param object $item    The current menu item.
    		 * @param array  $args    An array of {@see wp_nav_menu()} arguments.
    		 * @param int    $depth   Depth of menu item. Used for padding.
    		 */
    		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args, $depth );
    		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
    
    		$output .= $indent . '<li' . $id . $class_names .'>';
            
    		$atts = array();
    		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
    		$atts['target'] = ! empty( $item->target )     ? $item->target     : '';
    		$atts['rel']    = ! empty( $item->xfn )        ? $item->xfn        : '';
    		$atts['href']   = ! empty( $item->url )        ? $item->url        : '';
            
    		/**
    		 * Filter the HTML attributes applied to a menu item's anchor element.
    		 *
    		 * @since 3.6.0
    		 * @since 4.1.0 The `$depth` parameter was added.
    		 *
    		 * @param array $atts {
    		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
    		 *
    		 *     @type string $title  Title attribute.
    		 *     @type string $target Target attribute.
    		 *     @type string $rel    The rel attribute.
    		 *     @type string $href   The href attribute.
    		 * }
    		 * @param object $item  The current menu item.
    		 * @param array  $args  An array of {@see wp_nav_menu()} arguments.
    		 * @param int    $depth Depth of menu item. Used for padding.
    		 */
    		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );
    
    		$attributes = '';
    		foreach ( $atts as $attr => $value ) {
    			if ( ! empty( $value ) ) {
    				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
    				$attributes .= ' ' . $attr . '="' . $value . '"';
    			}
    		}
    
    		$item_output = $args->before;
    		$item_output .= '<a'. $attributes .'>';
    		/** This filter is documented in wp-includes/post-template.php */
    		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
    		$item_output .= '</a>';
            
            /** Added Modifications **/
            if($item->object == 'page' && $item->dispsubsec == 'yes') {
                $nav_code = $this->get_child_pages_nav_code($item->object_id);
                $item_output .= $nav_code;
            }
            /** End of Added Modifications **/
            
    		$item_output .= $args->after;
    
    		/**
    		 * Filter a menu item's starting output.
    		 *
    		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
    		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
    		 * no filter for modifying the opening and closing `<li>` for a menu item.
    		 *
    		 * @since 3.0.0
    		 *
    		 * @param string $item_output The menu item's starting HTML output.
    		 * @param object $item        Menu item data object.
    		 * @param int    $depth       Depth of menu item. Used for padding.
    		 * @param array  $args        An array of {@see wp_nav_menu()} arguments.
    		 */
    		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
    	}
    
    	/**
    	 * Ends the element output, if needed.
    	 *
    	 * @see Walker::end_el()
    	 *
    	 * @since 3.0.0
    	 *
    	 * @param string $output Passed by reference. Used to append additional content.
    	 * @param object $item   Page data object. Not used.
    	 * @param int    $depth  Depth of page. Not Used.
    	 * @param array  $args   An array of arguments. @see wp_nav_menu()
    	 */
    	public function end_el( &$output, $item, $depth = 0, $args = array() ) {
    		$output .= "</li>\n";
    	}
        
        public function get_child_pages_nav_code($par_id) {
            $sargs = array(
            	'post_parent' => $par_id,
            	'post_type' => 'page',
                'order' => 'ASC',
                'orderby' => 'menu_order',
            	'post_status' => 'publish'
            );
            $squery = new WP_Query($sargs);
            $nav_code = '';

            $pr_page = get_page($par_id);
            
            if($squery->have_posts()) {
                $nav_code .= '<ul class="sub-nav">';
                    while($squery->have_posts()) {
                        $squery->the_post();
                        global $post;
                        
                        $post_metaslug = get_post_meta( $post->ID, 'cus_page_id', true );
                        $sec_id = ($post_metaslug != '') ? esc_attr($post_metaslug) : $post->post_name;
                        $nav_code .= '<li><a href="'.esc_url(get_site_url()).'/'.$pr_page->post_name.'/#'.$sec_id.'">'.$post->post_title.'</a></li>';
                    }
                $nav_code .= '</ul>';
            }
            return $nav_code;
        }
    
    } // Walker_Nav_Menu
?>