<?php
/**
 * ParallaxSome Theme Customizer for homepage panel.
 *
 * @package AccessPress Themes
 * @subpackage ParallaxSome
 * @since 1.0.0
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */

if( ! function_exists( 'parallaxsome_homepage_panel_register' ) ):
	function parallaxsome_homepage_panel_register( $wp_customize ) {
		/**
		 * HomePage Settings Panel on customizer
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_panel(
	        'parallaxsome_homepage_settings_panel', 
	        	array(
	        		'priority'       => 15,
	            	'capability'     => 'edit_theme_options',
	            	'theme_supports' => '',
	            	'title'          => esc_html__( 'HomePage Settings', 'parallaxsome' ),
	            ) 
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Slider Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_slider_section',
		        array(
		            'title'		=> esc_html__( 'Slider Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 5,
		        )
	    );

	    /**
	     * Switch option for Homepage Slider Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_slider_option',
		        array(
		            'default' => 'hide',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
            'homepage_slider_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Slider Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for homepage Slider Section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_slider_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Dropdown available category for homepage slider
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'slider_cat_id',
		        array(
		            'default' => '0',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'absint'
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Category_Control(
	        $wp_customize,
	        'slider_cat_id', 
		        array(
		            'label' => esc_html__( 'Slider Category', 'parallaxsome' ),
		            'description' => esc_html__( 'Select cateogry for Homepage Slider Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_slider_section',
		            'priority' => 10
		        )
		    )
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * About Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_about_section',
		        array(
		            'title'		=> esc_html__( 'About Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 10,
		        )
	    );

	    /**
	     * Switch option for Homepage About Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_about_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_about_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage About Section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_about_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'about_section_title', 
	            array(
	                'default' => esc_html__( 'About', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'about_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_about_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'about_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Who We Are', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'about_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_about_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Dropdown available pages for homepage about section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'about_page_id',
		        array(
		            'default' => '0',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'absint'
		        )
	    );
	    $wp_customize->add_control(
	        'about_page_id', 
		        array(
		        	'type' => 'dropdown-pages',
		            'label' => esc_html__( 'About us Page', 'parallaxsome' ),
		            'description' => esc_html__( 'Select page for Homepage About Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_about_section',
		            'priority' => 20
		        )
	    );

	    /**
	     * Upload image control for section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'about_section_image',
		        array(
		            'default' => '',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'esc_url_raw'
		        )
	    );

	    $wp_customize->add_control( new WP_Customize_Image_Control(
	        $wp_customize,
	        'about_section_image',
	        	array(
	            	'label'      => esc_html__( 'Section Image', 'parallaxsome' ),
	               	'section'    => 'parallaxsome_about_section',
	               	'priority' => 25
	           	)
	       	)
	   	);
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Our Team Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_team_section',
		        array(
		            'title'		=> esc_html__( 'Our Team Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 15,
		        )
	    );

	    /**
	     * Switch option for Homepage Our Team Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_team_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_team_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Our Team Section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_team_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'team_section_title', 
	            array(
	                'default' => esc_html__( 'Our Team', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'team_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_team_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'team_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Group Together', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'team_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_team_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Field for section description
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'team_section_description', 
	            array(
	                'default' => '',
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'team_section_description',
	            array(
		            'type' => 'textarea',
		            'label' => esc_html__( 'Section Description', 'parallaxsome' ),
		            'section' => 'parallaxsome_team_section',
		            'priority' => 20
	            )
	    );

	    /**
	     * Dropdown available category for homepage our team
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'team_cat_id',
		        array(
		            'default' => '0',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'absint'
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Category_Control(
	        $wp_customize,
	        'team_cat_id', 
		        array(
		            'label' => esc_html__( 'Section Category', 'parallaxsome' ),
		            'description' => esc_html__( 'Select cateogry for Homepage Our Team Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_team_section',
		            'priority' => 25
		        )
		    )
	    );

	    /**
	     * Field for view all button text
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'team_view_more_txt', 
	            array(
	                'default' => esc_html__( 'View All', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'team_view_more_txt',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'View All Button', 'parallaxsome' ),
		            'section' => 'parallaxsome_team_section',
		            'priority' => 30
	            )
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Our Services Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_services_section',
		        array(
		            'title'		=> esc_html__( 'Our Services Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 20,
		        )
	    );

	    /**
	     * Switch option for Homepage Service Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_service_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_service_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Our Services Section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_services_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'services_section_title', 
	            array(
	                'default' => esc_html__( 'Our Services', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'services_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_services_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'services_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Our Works', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'services_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_services_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Field for section description
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'services_section_description', 
	            array(
	                'default' => '',
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'services_section_description',
	            array(
		            'type' => 'textarea',
		            'label' => esc_html__( 'Section Description', 'parallaxsome' ),
		            'section' => 'parallaxsome_services_section',
		            'priority' => 20
	            )
	    );

	    /**
	     * Upload image control for section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'service_bg_image',
		        array(
		            'default' => '',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'esc_url_raw'
		        )
	    );

	    $wp_customize->add_control( new WP_Customize_Image_Control(
	        $wp_customize,
	        'service_bg_image',
	        	array(
	            	'label'      => esc_html__( 'Section Background Image', 'parallaxsome' ),
	               	'section'    => 'parallaxsome_services_section',
	               	'priority' => 25
	           	)
	       	)
	   	);

	$service_priority = 30;
    $parallaxsome_default_service_icon = array( 'fa-flag', 'fa-database', 'fa-codepen', 'fa-hand-o-left', 'fa-coffee' );
    $prarallaxsome_separator_label = array( 'First', 'Second', 'Third', 'Forth', 'Fifth' );
    
    foreach ( $parallaxsome_default_service_icon as $icon_key => $icon_value ) {    	
		
	    /**
	     * Section separator
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'service_icon_sec_separator_'.$icon_key,
		        array(
		            'default' => '',
		            'sanitize_callback' => 'sanitize_text_field',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Section_Separator(
	        $wp_customize, 
	            'service_icon_sec_separator_'.$icon_key, 
	            array(
	                'type' 		=> 'parallaxsome_separator',
	                'label' 	=> sprintf(esc_html__( '%s Service', 'parallaxsome' ), $prarallaxsome_separator_label[$icon_key] ),
	                'section' 	=> 'parallaxsome_services_section',
	                'priority'  => $service_priority,
	            )	            	
	        )
	    );

	    /**
	     * Icon list for service tab
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'service_icon_'.$icon_key,
		        array(
		            'default' => $icon_value,
		            'sanitize_callback' => 'sanitize_text_field',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Icons_Control(
	        $wp_customize, 
	        'service_icon_'.$icon_key, 
	            array(
	                'type' 		=> 'parallaxsome_icons',	                
	                'label' 	=> esc_html__( 'Service Icon', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Choose the icon from lists.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_services_section',
	                'priority'  => $service_priority,
	            )	            	
	        )
	    );

	    /**
	     * Dropdown available pages for homepage about section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'service_page_id_'.$icon_key,
		        array(
		            'default' => '0',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'absint'
		        )
	    );
	    $wp_customize->add_control(
	        'service_page_id_'.$icon_key,
		        array(
		        	'type' => 'dropdown-pages',
		            'label' => esc_html__( 'Service Page', 'parallaxsome' ),
		            'description' => esc_html__( 'Select page for Homepage Service Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_services_section',
		            'priority' => $service_priority
		        )
	    );
	    $service_priority = $service_priority+5;
	}


/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Testimonials Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_testimonials_section',
		        array(
		            'title'		=> esc_html__( 'Testimonials Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 25,
		        )
	    );

	    /**
	     * Switch option for Homepage Testimonials Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_testimonials_option',
	        	array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	        'homepage_testimonials_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Testimonials Section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_testimonials_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'testimonials_section_title', 
	            array(
	                'default' => esc_html__( 'Clients Say', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'testimonials_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_testimonials_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'testimonials_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Group Together', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       )
	    );
	    $wp_customize->add_control(
	        'testimonials_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_testimonials_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Dropdown available category for homepage Testimonials
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'testimonials_cat_id',
		        array(
		            'default' => '0',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'absint'
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Category_Control(
	        $wp_customize,
	        'testimonials_cat_id', 
		        array(
		            'label' => esc_html__( 'Section Category', 'parallaxsome' ),
		            'description' => esc_html__( 'Select cateogry for Homepage Testimonials Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_testimonials_section',
		            'priority' => 20
	            )
	        )
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Our Fact Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_fact_section',
		        array(
		            'title'		=> esc_html__( 'Our Facts Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 30,
		        )
	    );

	    /**
	     * Switch option for Homepage Our Fact Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_fact_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_fact_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Our Facts.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_fact_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_section_title', 
	            array(
	                'default' => esc_html__( 'Fact About Us', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'fact_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_fact_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub-title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Our Works', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'fact_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_fact_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Field for section description
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_section_description', 
	            array(
	                'default' => '',
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'fact_section_description',
	            array(
		            'type' => 'textarea',
		            'label' => esc_html__( 'Section Description', 'parallaxsome' ),
		            'section' => 'parallaxsome_fact_section',
		            'priority' => 20
	            )
	    );

	    /**
	     * Upload image control for section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_bg_image',
		        array(
		            'default' => '',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'esc_url_raw'
		        )
	    );

	    $wp_customize->add_control( new WP_Customize_Image_Control(
	        $wp_customize,
	        'fact_bg_image',
	        	array(
	            	'label'      => esc_html__( 'Section Background Image', 'parallaxsome' ),
	               	'section'    => 'parallaxsome_fact_section',
	               	'priority' => 25
	           	)
	       	)
	   	);

		$fact_priority = 30;
		$prarallaxsome_separator_label = array( 'First', 'Second', 'Third', 'Forth' );
		$parallaxsome_default_fact_icon = array( 'fa-coffee', 'fa-rocket', 'fa-code', 'fa-thumbs-o-up' );
		$parallaxsome_default_fact_title = array( 'Cups Consumed', 'Projects Lunched', 'Lines of Code', 'Satisfied Clients' );
		$parallaxsome_default_fact_number = array( '798', '237', '54871', '25084' );
	/*for ( $fact_count=1; $fact_count <= 4 ; $fact_count++) {*/
	foreach ( $parallaxsome_default_fact_icon as $icon_key => $icon_value ) {
		
	    /**
	     * Section separator
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_icon_sec_separator_'.$icon_key,
		        array(
		            'default' => '',
		            'sanitize_callback' => 'sanitize_text_field',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Section_Separator(
	        $wp_customize, 
	            'fact_icon_sec_separator_'.$icon_key, 
	            array(
	                'type' 		=> 'parallaxsome_separator',
	                'label' 	=> sprintf(esc_html__( '%s Fact Counter', 'parallaxsome' ), $prarallaxsome_separator_label[$icon_key] ),
	                'section' 	=> 'parallaxsome_fact_section',
	                'priority'  => $fact_priority,
	            )	            	
	        )
	    );


	    /**
	     * Icon list for fact counter
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_icon_'.$icon_key,
		        array(
		            'default' => $parallaxsome_default_fact_icon[$icon_key],
		            'sanitize_callback' => 'sanitize_text_field',
		            'transport' => 'postMessage'
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Icons_Control(
	        $wp_customize, 
	            'fact_icon_'.$icon_key, 
	            array(
	                'type' 		=> 'parallaxsome_icons',	                
	                'label' 	=> esc_html__( 'Fact Icon', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Choose the icon from lists.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_fact_section',
	                'priority'  => $fact_priority,
	            )	            	
	        )
	    );

	    /**
	     * Field for counter title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_counter_title_'.$icon_key, 
	            array(
	                'default' => sprintf( esc_html( '%s', 'parallaxsome' ), $parallaxsome_default_fact_title[$icon_key] ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       )
	    );    
	    $wp_customize->add_control(
	        'fact_counter_title_'.$icon_key,
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Fact Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_fact_section',
		            'priority' => $fact_priority
	            )
	    );

	    /**
	     * Field for counter number
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'fact_counter_number_'.$icon_key, 
	            array(
	            	'default' => $parallaxsome_default_fact_number[$icon_key],
	                'sanitize_callback' => 'parallaxsome_sanitize_number',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'fact_counter_number_'.$icon_key,
	            array(
		            'type' => 'number',
		            'label' => esc_html__( 'Fact Number', 'parallaxsome' ),
		            'section' => 'parallaxsome_fact_section',
		            'priority' => $fact_priority
	            )
	    );
	    $fact_priority = $fact_priority+5;
	}
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Our Portfolio Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_portfolio_section',
		        array(
		            'title'		=> esc_html__( 'PortFolio Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 35,
		        )
	    );

	    /**
	     * Switch option for Homepage Portfolio Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_portfolio_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	        'homepage_portfolio_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Portfolio section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_portfolio_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'portfolio_section_title', 
	            array(
	                'default' => esc_html__( 'Portfolio', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'portfolio_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_portfolio_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub-title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'portfolio_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Best Porjects', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'portfolio_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_portfolio_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Field for section description
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'portfolio_section_description', 
	            array(
	                'default' => '',
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'portfolio_section_description',
	            array(
		            'type' => 'textarea',
		            'label' => esc_html__( 'Section Description', 'parallaxsome' ),
		            'section' => 'parallaxsome_portfolio_section',
		            'priority' => 20
	            )
	    );

	    /**
	     * Dropdown available category for homepage Testimonials
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'portfolio_cat_id',
	        array(
	            'default' => '0',
	            'capability' => 'edit_theme_options',
	            'sanitize_callback' => 'absint'
	        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Category_Control(
	        $wp_customize,
	        'portfolio_cat_id', 
		        array(
		            'label' => esc_html__( 'Section Category', 'parallaxsome' ),
		            'description' => esc_html__( 'Select cateogry for Homepage Portfolio Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_portfolio_section',
		            'priority' => 25
	            )
	        )
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Our Blog Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_blog_section',
	        array(
	            'title'		=> esc_html__( 'Blog Section', 'parallaxsome' ),
	            'panel'     => 'parallaxsome_homepage_settings_panel',
	            'priority'  => 35,
	        )
	    );

	    /**
	     * Switch option for Homepage Portfolio Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_blog_option',
	        array(
	            'default' => 'show',
	            'transport' => 'postMessage',
	            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
	            )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_blog_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Latest Blog section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_blog_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'blog_section_title', 
	            array(
	                'default' => esc_html__( 'Our Blog', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       )
	    );
	    $wp_customize->add_control(
	        'blog_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_blog_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub-title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'blog_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Latest News', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       )
	    );
	    $wp_customize->add_control(
	        'blog_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_blog_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Mulitple checkboxes for category
	     *
	     * @since 1.0.0
	     */
	    global $parallaxsome_categories;
	    $wp_customize->add_setting(
	        'ps_blog_categories',
		        array(
		            'default'           => array( 'uncategorized' ),
		            'sanitize_callback' => 'parallaxsome_multiple_categories_sanitize'
		        )
	    );

	    $wp_customize->add_control( new Parallaxsome_Customize_Control_Checkbox_Multiple(
	        $wp_customize,
	        'ps_blog_categories',
	            array(
	                'section' => 'parallaxsome_blog_section',
	                'label'   => esc_html__( 'Section Categories Lists', 'parallaxsome' ),
	                'priority' => 20,
	                'choices' => $parallaxsome_categories
	            )
	        )
	    );

	    /**
	     * Field for Read more button
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'blog_section_read_button', 
	            array(
	                'default' => esc_html__( 'Read More', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'blog_section_read_button',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Read More Button', 'parallaxsome' ),
		            'section' => 'parallaxsome_blog_section',
		            'priority' => 25
	            )
	    );

	    /**
	     * Field for View more button
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'blog_section_view_all_txt', 
	            array(
	                'default' => esc_html__( 'View All', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'blog_section_view_all_txt',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'View All Button', 'parallaxsome' ),
		            'section' => 'parallaxsome_blog_section',
		            'priority' => 30
	            )
	    );

	    /**
	     * Field for View more button link
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'blog_section_view_all_link', 
	            array(
	                'default' => '',
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );    
	    $wp_customize->add_control(
	        'blog_section_view_all_link',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'View All Button Link', 'parallaxsome' ),
		            'section' => 'parallaxsome_blog_section',
		            'priority' => 35
	            )
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Our Clients Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_client_section',
		        array(
		            'title'		=> esc_html__( 'Our Clients Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 40,
		        )
	    );

	    /**
	     * Switch option for Homepage Portfolio Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_clients_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
	            )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_clients_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Our Clients section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_client_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'clients_section_title', 
	            array(
	                'default' => esc_html__( 'Our Clients', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'clients_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_client_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub-title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'clients_section_sub_title', 
	            array(
	                'default' => esc_html__( 'Latest News', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'clients_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_client_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Dropdown available category for homepage clients
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'clients_cat_id',
		        array(
		            'default' => '0',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'absint'
		        )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Category_Control(
	        $wp_customize,
	        'clients_cat_id',
		        array(
		            'label' => esc_html__( 'Section Category', 'parallaxsome' ),
		            'description' => esc_html__( 'Select cateogry for Homepage Our Clients Section', 'parallaxsome' ),
		            'section' => 'parallaxsome_client_section',
		            'priority' => 20
	            )
	        )
	    );

	    /**
	     * Upload image control for section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'clients_bg_image',
		        array(
		            'default' => '',
		            'capability' => 'edit_theme_options',
		            'sanitize_callback' => 'esc_url_raw'
		        )
	    );

	    $wp_customize->add_control( new WP_Customize_Image_Control(
	        $wp_customize,
	        'clients_bg_image',
	           	array(
	               'label'      => esc_html__( 'Section Background Image', 'parallaxsome' ),
	               'section'    => 'parallaxsome_client_section',
	               'priority' => 25
	           	)
	       )
	    );
/*--------------------------------------------------------------------------------------------------------------*/
		/**
		 * Contact Us Section
		 *
		 * @since 1.0.0
		 */
		$wp_customize->add_section(
	        'parallaxsome_contact_section',
		        array(
		            'title'		=> esc_html__( 'Contact Us Section', 'parallaxsome' ),
		            'panel'     => 'parallaxsome_homepage_settings_panel',
		            'priority'  => 45,
		        )
	    );

	    /**
	     * Switch option for Homepage Contact us Section
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'homepage_contact_option',
		        array(
		            'default' => 'show',
		            'transport' => 'postMessage',
		            'sanitize_callback' => 'parallaxsome_sanitize_switch_option',
	            )
	    );
	    $wp_customize->add_control( new Parallaxsome_Customize_Switch_Control(
	        $wp_customize, 
	            'homepage_contact_option', 
	            array(
	                'type' 		=> 'switch',	                
	                'label' 	=> esc_html__( 'Section Option', 'parallaxsome' ),
	                'description' 	=> esc_html__( 'Show/hide option for Homepage Contact Us section.', 'parallaxsome' ),
	                'section' 	=> 'parallaxsome_contact_section',
	                'choices'   => array(
	                    'show' 	=> esc_html__( 'Show', 'parallaxsome' ),
	                    'hide' 	=> esc_html__( 'Hide', 'parallaxsome' )
	                    ),
	                'priority'  => 5,
	            )
	        )
	    );

	    /**
	     * Field for section title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'contact_section_title', 
	            array(
	                'default' => esc_html__( 'Contact Us', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'contact_section_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_contact_section',
		            'priority' => 10
	            )
	    );

	    /**
	     * Field for section sub-title
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'contact_section_sub_title', 
	            array(
	                'default' => esc_html__( 'More Info', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'contact_section_sub_title',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Section Sub Title', 'parallaxsome' ),
		            'section' => 'parallaxsome_contact_section',
		            'priority' => 15
	            )
	    );

	    /**
	     * Field for Text editor
	     *
	     * @since 1.0.0
	     */
		
        $wp_customize->add_setting( 'contact_section_form_page', array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		) );

		$wp_customize->add_control( 'contact_section_form_page', array(
			'label'       => esc_html__( 'Contact Form Page', 'parallaxsome' ),
			'description' => esc_html__( 'Use contact form 7 shortcode.', 'parallaxsome' ),
			'section'     => 'parallaxsome_contact_section',
			'priority'	  => 20,
			'type'     => 'dropdown-pages'
		) );
	    /**
	     * Field for section contact number
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'contact_section_phone', 
	            array(
	                'default' => esc_html__( '(44) 123 456 7894', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'contact_section_phone',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Call Us', 'parallaxsome' ),
		            'section' => 'parallaxsome_contact_section',
		            'priority' => 25
	            )
	    );

	    /**
	     * Field for section contact address
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'contact_section_address',
	            array(
	                'default' => esc_html__( 'Alaxender Avenue, Harrow, Middlesex, UK', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'contact_section_address',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Address', 'parallaxsome' ),
		            'section' => 'parallaxsome_contact_section',
		            'priority' => 30
	            )
	    );

	    /**
	     * Field for contact map caption
	     *
	     * @since 1.0.0
	     */
	    $wp_customize->add_setting(
	        'contact_map_caption', 
	            array(
	                'default' => esc_html__( 'Locate Us on Map', 'parallaxsome' ),
	                'sanitize_callback' => 'sanitize_text_field',
	                'transport' => 'postMessage'
		       	)
	    );
	    $wp_customize->add_control(
	        'contact_map_caption',
	            array(
		            'type' => 'text',
		            'label' => esc_html__( 'Map Caption', 'parallaxsome' ),
		            'section' => 'parallaxsome_contact_section',
		            'priority' => 35
	            )
	    );



	} //close fucntion
endif;
add_action( 'customize_register', 'parallaxsome_homepage_panel_register' );