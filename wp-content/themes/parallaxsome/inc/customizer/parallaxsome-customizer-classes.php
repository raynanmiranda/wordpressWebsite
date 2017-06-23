<?php
/**
 * Define some custom classes for parallaxsome.
 * 
 * https://codex.wordpress.org/Class_Reference/WP_Customize_Control
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
     * Switch button customize control.
     *
     * @since 1.0.0
     * @access public
     */
    class Parallaxsome_Customize_Switch_Control extends WP_Customize_Control {

    	/**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
		public $type = 'switch';

		/**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
		public function render_content() {
	?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div class="description customize-control-description"><?php echo esc_html( $this->description ); ?></div>
		        <div class="switch_options">
		        	<?php 
		        		$show_choices = $this->choices;
		        		foreach ( $show_choices as $key => $value ) {
		        			echo '<span class="switch_part '.esc_attr($key).'" data-switch="'.esc_attr($key).'">'. esc_attr($value).'</span>';
		        		}
		        	?>
                  	<input type="hidden" id="ap_switch_option" <?php $this->link(); ?> value="<?php echo $this->value(); ?>" />
                </div>
            </label>
	<?php
		}
	}

	/**
	 * A class to create a dropdown for all categories in your wordpress site
	 *
	 * @since 1.0.0
	 * @access public
	 */
	class Parallaxsome_Customize_Category_Control extends WP_Customize_Control {
		
		/**
		 * Render the control's content.
		 *
		 * @return HTML
		 * @since 1.0.0
		 */
		public function render_content() {
			$dropdown = wp_dropdown_categories(
					array(
						'name'              => '_customize-dropdown-categories-' . $this->id,
						'echo'              => 0,
						'show_option_none'  => esc_html__( '&mdash; Select Category &mdash;', 'parallaxsome' ),
						'option_none_value' => '0',
						'selected'          => $this->value(),
					)
			);

			// Hackily add in the data link parameter.
			$dropdown = str_replace( '<select', '<select ' . $this->get_link(), $dropdown );

			printf(
				'<label class="customize-control-select"><span class="customize-control-title">%s</span><span class="description customize-control-description">%s</span> %s </label>',
				$this->label,
				$this->description,
				$dropdown
			);
		}
	}

	/**
	 * A class to create a list of icons in customizer field
	 *
	 * @since 1.0.0
	 * @access public
	 */
	class Parallaxsome_Customize_Icons_Control extends WP_Customize_Control {

		/**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
		public $type = 'parallaxsome_icons';

		/**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
		public function render_content() {

			$saved_icon_value = $this->value();
	?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<div class="ap-customize-icons">
					<div class="selected-icon-preview"><?php if( !empty( $saved_icon_value ) ) { echo '<i class="fa '. esc_attr($saved_icon_value) .'"></i>'; } ?></div>
					<ul class="icons-list-wrapper">
						<?php 
							$parallaxsome_icons_list = parallaxsome_icons_array();
							foreach ( $parallaxsome_icons_list as $key => $icon_value ) {
								if( $saved_icon_value == $icon_value ) {
									echo '<li class="selected"><i class="fa '. esc_attr($icon_value) .'"></i></li>';
								} else {
									echo '<li><i class="fa '. esc_attr($icon_value) .'"></i></li>';
								}
							}
						?>
					</ul>
					<input type="hidden" class="ap-icon-value" value="" <?php $this->link(); ?>>
				</div>

			</label>
	<?php
		}
	}

	/**
	 * A class to create a option separator in customizer section 
	 *
	 *@since 1.0.0
	 */
	class Parallaxsome_Customize_Section_Separator extends WP_Customize_Control {
		/**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
		public $type = 'parallaxsome_separator';

		/**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
		public function render_content() {
	?>
		<div class="ap-customize-section-wrap">
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			</label>
		</div>
	<?php
		}
	}

	/**
	 * Multiple checkbox customize control class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	class Parallaxsome_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

	    /**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
	    public $type = 'checkbox-multiple';

	    /**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
	    public function render_content() {

	        if ( empty( $this->choices ) )
	            return; ?>

	        <?php if ( !empty( $this->label ) ) : ?>
	            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	        <?php endif; ?>

	        <?php if ( !empty( $this->description ) ) : ?>
	            <span class="description customize-control-description"><?php echo esc_textarea($this->description); ?></span>
	        <?php endif; ?>

	        <?php $multi_values = !is_array( $this->value() ) ? explode( ',', $this->value() ) : $this->value(); ?>

	        <ul>
	            <?php foreach ( $this->choices as $value => $label ) : ?>

	                <li>
	                    <label>
	                        <input type="checkbox" value="<?php echo esc_attr( $value ); ?>" <?php checked( in_array( $value, $multi_values ) ); ?> /> 
	                        <?php echo esc_html( $label ); ?>
	                    </label>
	                </li>

	            <?php endforeach; ?>
	        </ul>

	        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr( implode( ',', $multi_values ) ); ?>" />
	    <?php }
	}

	/**
	 * Class to create a custom editor field in customizer section
	 *
	 * @access public
	 * @since 1.0.0
	 */
	class Parallaxsome_Text_Editor_Custom_Control extends WP_Customize_Control {
	    /**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
	    public $type = 'parallaxsome-editor';

		/**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
		public function render_content() { ?>
			
			<label>
				<span class="customize-control-title">
					<?php echo esc_attr( $this->label ); ?>
				</span>
				<input type="hidden" <?php $this->link(); ?> value="<?php echo esc_textarea( $this->value() ); ?>">
			</label>

		<?php
			$settings = array(
				'textarea_name'    => $this->id,
				'teeny'            => true,
			);
			wp_editor( esc_textarea( $this->value() ), $this->id, $settings );

			do_action('admin_print_footer_scripts');
		}
	}

	/**
	 * Radio image customize control.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	class Parallaxsome_Customize_Control_Radio_Image extends WP_Customize_Control {
	    /**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
	    public $type = 'radio-image';

	    /**
	     * Loads the jQuery UI Button script and custom scripts/styles.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
	    /*public function enqueue() {
	        wp_enqueue_script( 'jquery-ui-button' );
	    }*/

	    /**
	     * Add custom JSON parameters to use in the JS template.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
	    public function to_json() {
	        parent::to_json();

	        // We need to make sure we have the correct image URL.
	        foreach ( $this->choices as $value => $args )
	            $this->choices[ $value ]['url'] = esc_url( sprintf( $args['url'], get_template_directory_uri(), get_stylesheet_directory_uri() ) );

	        $this->json['choices'] = $this->choices;
	        $this->json['link']    = $this->get_link();
	        $this->json['value']   = $this->value();
	        $this->json['id']      = $this->id;
	    }


	    /**
	     * Underscore JS template to handle the control's output.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */

	    public function content_template() { ?>
	        <# if ( data.label ) { #>
	            <span class="customize-control-title">{{ data.label }}</span>
	        <# } #>

	        <# if ( data.description ) { #>
	            <span class="description customize-control-description">{{{ data.description }}}</span>
	        <# } #>

	        <div class="buttonset">

	            <# for ( key in data.choices ) { #>

	                <input type="radio" value="{{ key }}" name="_customize-{{ data.type }}-{{ data.id }}" id="{{ data.id }}-{{ key }}" {{{ data.link }}} <# if ( key === data.value ) { #> checked="checked" <# } #> /> 

	                <label for="{{ data.id }}-{{ key }}">
	                    <span class="screen-reader-text">{{ data.choices[ key ]['label'] }}</span>
	                    <img src="{{ data.choices[ key ]['url'] }}" title="{{ data.choices[ key ]['label'] }}" alt="{{ data.choices[ key ]['label'] }}" />
	                </label>
	            <# } #>

	        </div><!-- .buttonset -->
	    <?php }
	}
    
    /**
     * Theme info
     */
    class parallaxsome_Info_Custom_Control extends WP_Customize_Control {
        public function render_content(){

            $important_links = array(
                'demo' => array(
                   'link' => esc_url( 'http://accesspressthemes.com/theme-demos/?theme=parallaxsome' ),
                   'text' => __( 'View Demo', 'parallaxsome' ),
                ),
                'documentation' => array(
                   'link' => esc_url( 'http://doc.accesspressthemes.com/parallaxsome/' ),
                   'text' => __( 'Documentation', 'parallaxsome' ),
                ),
                'theme-info' => array(
                   'link' => esc_url( 'https://accesspressthemes.com/wordpress-themes/parallaxsome/' ),
                   'text' => __( 'Theme Info', 'parallaxsome' ),
                ),
                'support' => array(
                   'link' => esc_url( 'https://accesspressthemes.com/support/forum/themes/free-themes/parallaxsome/' ),
                   'text' => __( 'Support', 'parallaxsome' ),
                ),
                'rating' => array(
                   'link' => esc_url( 'https://wordpress.org/support/theme/parallaxsome/reviews/?filter=5' ),
                   'text' => __( 'Rate This Theme', 'parallaxsome' ),
                ),
                'resources' => array(
                   'link' => esc_url( 'http://wpall.club/' ),
                   'text' => __( 'More WordPress Resources', 'parallaxsome' ),
                ),
            );
            foreach ( $important_links as $important_link ) {
                echo '<p><a target="_blank" href="' . $important_link['link'] . '" >' . esc_html( $important_link['text'] ) . ' </a></p>';
            }
        ?>
        	<label>
        	    <h2 class="customize-title"><?php echo esc_html( $this->label ); ?></h2>
        	    <span class="customize-text_editor_desc">                 
        	        <ul class="admin-pro-feature-list">   
        	            <li><span><?php _e('One Page Design With Parallax Scrolling Effect','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Live Customizer Based','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Fullwidth Slider','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Parallax Menu With floating Option','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Highly Configurable Homepage','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Background Configuration','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Our Team Section','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Counter Up Section','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Our Services Section','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Blog Section','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Our Client section','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Contact section with fullwidth map area','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Testimonial Section','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Fast Loading Speed','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Cross Browser Compatible','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Extensive Detailed Documentation','parallaxsome'); ?> </span></li>
        	            <li><span><?php _e('Translation Ready','parallaxsome'); ?> </span></li>
        	        </ul>
        	    </span>
        	</label>
        <?php
        }
    }
}