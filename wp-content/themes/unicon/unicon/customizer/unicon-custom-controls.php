<?php
/**
 * Define some custom classes for unicon.
 * 
 * https://codex.wordpress.org/Class_Reference/WP_Customize_Control
 *
 * @package AccessPress Themes
 * @subpackage Unicon
 * @since 1.0.0
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
     * Switch button customize control.
     *
     * @since 1.0.0
     * @access public
     */
    class Unicon_Customize_Switch_Control extends WP_Customize_Control {

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
		public function render_content() { ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<div class="description customize-control-description"><?php echo esc_html( $this->description ); ?></div>
		        <div class="switch_options">
		        	<?php 
		        		$show_choices = $this->choices;
		        		foreach ( $show_choices as $key => $value ) {
		        			echo '<span class="switch_part '.$key.'" data-switch="'.$key.'">'. $value.'</span>';
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
	class Unicon_Customize_Category_Control extends WP_Customize_Control {
		
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
					'show_option_none'  => __( '&mdash; Select Category &mdash;', 'unicon' ),
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
	class Unicon_Customize_Icons_Control extends WP_Customize_Control {

		/**
	     * The type of customize control being rendered.
	     *
	     * @since  1.0.0
	     * @access public
	     * @var    string
	     */
		public $type = 'unicon_icons';

		/**
	     * Displays the control content.
	     *
	     * @since  1.0.0
	     * @access public
	     * @return void
	     */
		public function render_content() {

			$saved_icon_value = $this->value(); ?>
			<label>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<div class="ap-customize-icons">
					<div class="selected-icon-preview"><?php if( !empty( $saved_icon_value ) ) { echo '<i class="fa '. $saved_icon_value .'"></i>'; } ?></div>
					<ul class="icons-list-wrapper">
						<?php 
							$unicon_icons_list = unicon_icons_array();
							foreach ( $unicon_icons_list as $key => $icon_value ) {
								if( $saved_icon_value == $icon_value ) {
									echo '<li class="selected"><i class="fa '. $icon_value .'"></i></li>';
								} else {
									echo '<li><i class="fa '. $icon_value .'"></i></li>';
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
	* Multiple checkbox customize control class.
	*
	* @since  1.0.0
	* @access public
	*/
	class Unicon_Customize_Control_Checkbox_Multiple extends WP_Customize_Control {

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
	           <span class="description customize-control-description"><?php echo $this->description; ?></span>
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
	 * A class to create a option separator in customizer section 
	 *
	 *@since 1.0.0
	 */
	class unicon_Customize_Section_Separator extends WP_Customize_Control {

		public $type = 'unicon_separator';

		public function render_content() { ?>
			<div class="serviceswrap">
				<label>
					<span class="sep-title"><?php echo esc_html( $this->label ); ?></span>
				</label>
			</div>
	    <?php }
	}
}
