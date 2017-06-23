<?php
/* Aobut Section */
$wp_customize->add_section( 'unicon_about_section', array(
    'title'		=> __( 'About Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 1,
) );

	$wp_customize->add_setting( 'unicon_about_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_about_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_about_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* About Main Section Title */
	$wp_customize->add_setting( 'unicon_about_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Company','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_about_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_about_section',
	    'settings' => 'unicon_about_section_main_title'
	) );

	/* About Sub Section Title */
	$wp_customize->add_setting( 'unicon_about_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('We Are Goahead','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_about_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_about_section',
	    'settings' => 'unicon_about_section_sub_title'
	) );

	/* About Section Page */
	$wp_customize->add_setting( 'unicon_about_section_page', array(
		'default'           => '0',
		'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( 'unicon_about_section_page', array(
		'label'    => __( 'Select About Section Page', 'unicon' ),
		'section'  => 'unicon_about_section',
		'type'     => 'dropdown-pages'
	) );

	/* About Section Category */
	$wp_customize->add_setting( 'unicon_team_id', array(
        'default' => '0',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( new unicon_Customize_Category_Control( $wp_customize, 'unicon_team_id', array(
	    'label' => __( 'Select About Section Category', 'unicon' ),
	    'section' => 'unicon_about_section',
	) ) );


/* Services Section */
$wp_customize->add_section( 'unicon_services_section', array(
    'title'		=> __( 'Services Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 2,
) );

	$wp_customize->add_setting( 'unicon_services_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_services_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_services_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Services Main Section Title */
	$wp_customize->add_setting( 'unicon_services_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Services','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_services_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_services_section',
	    'settings' => 'unicon_services_section_main_title'
	) );

	/* Services Sub Section Title */
	$wp_customize->add_setting( 'unicon_services_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('What We Do','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_services_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_services_section',
	    'settings' => 'unicon_services_section_sub_title'
	) );

	/* Our Services Full Background Image */
	$wp_customize->add_setting( 'unicon_services_bg_image', array(
	    'default'       =>      '',
	    'sanitize_callback' => 'esc_url_raw'  // done
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'unicon_services_bg_image', array(
	    'section'    =>      'unicon_services_section',
	    'label'      =>      __('Upload Services Area Background Image', 'unicon'),
	    'type'       =>      'image',
	) ) );


    $unicon_default_service_icon = array( 'fa-flag', 'fa-database', 'fa-codepen', 'fa-hand-o-left', 'fa-coffee', 'fa-coffee' );
    $unicon_separator_label = array( 'First', 'Second', 'Third', 'Forth', 'Fifth', 'Sixth' );
    foreach ( $unicon_default_service_icon as $icon_key => $icon_value ) { 
	    /**
	     * Our Services Section Separator
	    */
	    $wp_customize->add_setting( 'service_icon_sec_separator_'.$icon_key, array(
            'default' => '',
            'sanitize_callback' => 'unicon_text_sanitize',
        ) );

	    $wp_customize->add_control( new unicon_Customize_Section_Separator( $wp_customize,  'service_icon_sec_separator_'.$icon_key,  array(
            'type' 		=> 'unicon_separator',
            'label' 	=> sprintf(esc_html__( '%s Service Section', 'unicon' ), $unicon_separator_label[$icon_key] ),
            'section' 	=> 'unicon_services_section',
        ) ) );

	    /**
	     * Select Our Services Icon
	    */
   		$wp_customize->add_setting( 'service_icon_'.$icon_key, array(
        	'default' => $icon_value,
        	'sanitize_callback' => 'unicon_text_sanitize',
    	) );
    	$wp_customize->add_control( new Unicon_Customize_Icons_Control( $wp_customize, 'service_icon_'.$icon_key, array(
            'type' 		=> 'unicon_icons',	                
            'label' 	=> esc_html__( 'Our Service Icon', 'unicon' ),
            'description' 	=> esc_html__( 'Choose the service icon from the lists.', 'unicon' ),
            'section' 	=> 'unicon_services_section',
        ) ) );

	    /**
	     * Select Our Services Page in Dropdown Options
	    */
	    $wp_customize->add_setting( 'service_page_id_'.$icon_key, array(
            'default' => '0',
            'capability' => 'edit_theme_options',
            'sanitize_callback' => 'absint'
        ) );

	    $wp_customize->add_control( 'service_page_id_'.$icon_key, array(
        	'type' => 'dropdown-pages',
            'label' => esc_html__( 'Select Service Page', 'unicon' ),
            'section' => 'unicon_services_section'
        ) );

	}


/* Sucess Graph Section */
$wp_customize->add_section( 'unicon_sucess_graph_section', array(
    'title'		=> __( 'Sucess Graph Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 3,
) );

	$wp_customize->add_setting( 'unicon_sucess_graph_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_sucess_graph_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_sucess_graph_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Sucess Graph Main Section Title */
	$wp_customize->add_setting( 'unicon_sucess_graph_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Sucess','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_sucess_graph_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_sucess_graph_section',
	    'settings' => 'unicon_sucess_graph_section_main_title'
	) );

	/* Sucess Graph Sub Section Title */
	$wp_customize->add_setting( 'unicon_sucess_graph_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Yearly Graph','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_sucess_graph_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_sucess_graph_section',
	    'settings' => 'unicon_sucess_graph_section_sub_title'
	) );

	/* Sucess Graph Section Page */
	$wp_customize->add_setting( 'unicon_sucess_graph_section_page', array(
		'default'           => '0',
		'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( 'unicon_sucess_graph_section_page', array(
		'label'    => __( 'Select Sucess Graph Section Page', 'unicon' ),
		'section'  => 'unicon_sucess_graph_section',
		'type'     => 'dropdown-pages'
	) );

	for ( $count = 1; $count <= 8; $count++ ) {

		/* Sucess Graph Yearly Percentage Section */
		$wp_customize->add_setting( 'unicon_sucess_graph_percentage_' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'absint'
		) );

		$wp_customize->add_control( 'unicon_sucess_graph_percentage_' . $count, array(
			'label'    => __( 'Enter the Yearly Sucess Percentage', 'unicon' ),
			'section'  => 'unicon_sucess_graph_section'
		) );

		/* Sucess Graph Yearly Section */
		$wp_customize->add_setting( 'unicon_sucess_graph_year_' . $count, array(
			'default'           => '',
			'sanitize_callback' => 'unicon_text_sanitize'
		) );

		$wp_customize->add_control( 'unicon_sucess_graph_year_' . $count, array(
			'type'    => 'number',
			'label'    => __( 'Enter the Sucess Yearly ', 'unicon' ),
			'section'  => 'unicon_sucess_graph_section'
		) );

	}


/* Sucess Graph Section */
$wp_customize->add_section( 'unicon_counter_section', array(
    'title'		=> __( 'Counter Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 4,
) );
	
	$wp_customize->add_setting( 'unicon_counter_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_counter_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_counter_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );


	for ( $count = 1; $count <= 4; $count++ ) {

		/* Counter Section Title */
		$wp_customize->add_setting( 'unicon_counter_section_title_' . $count, array(
			'default'           => '<span>We Have</span> Completed Projects',
			'sanitize_callback' => 'unicon_text_sanitize',
			'transport' => 'postMessage'
		) );

		$wp_customize->add_control( 'unicon_counter_section_title_' . $count, array(
			'label'    => __( 'Enter the Counter Title', 'unicon' ),
			'section'  => 'unicon_counter_section'
		) );

		/* Counter Section Number */
		$wp_customize->add_setting( 'unicon_counter_section_number_' . $count, array(
			'default'           => '272',
			'sanitize_callback' => 'absint',
			'transport' => 'postMessage'
		) );

		$wp_customize->add_control( 'unicon_counter_section_number_' . $count, array(
			'label'    => __( 'Enter the Counter Number', 'unicon' ),
			'section'  => 'unicon_counter_section'
		) );

		/* Counter Section Icon */
		$wp_customize->add_setting( 'unicon_counter_section_icon_' . $count, array(
			'default'           => 'fa fa-envelope',
			'sanitize_callback' => 'unicon_text_sanitize',
			'transport' => 'postMessage'
		) );

		$wp_customize->add_control( 'unicon_counter_section_icon_' . $count, array(
			'type'    => 'text',
			'label'    => __( 'Enter the Counter Icon', 'unicon' ),
		    'description' => sprintf( __( 'Use font awesome icon: Eg: %s. %sSee more here%s', 'unicon' ), 'fa fa-truck','<a href="'.esc_url('http://fontawesome.io/cheatsheet/').'" target="_blank">','</a>' ),
			'section'  => 'unicon_counter_section'
		) );

	}


/* Our Team Member Section */
$wp_customize->add_section( 'unicon_team_section', array(
    'title'		=> __( 'Our Team Member Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 5,
) );

	$wp_customize->add_setting( 'unicon_team_section_option', array(
	    'default' => 'hide',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_team_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_team_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Our Team Member Main Section Title */
	$wp_customize->add_setting( 'unicon_team_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Our Team','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_team_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_team_section',
	    'settings' => 'unicon_team_section_main_title'
	) );

	/* Our Team Member Sub Section Title */
	$wp_customize->add_setting( 'unicon_team_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Active Expert','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_team_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_team_section',
	    'settings' => 'unicon_team_section_sub_title'
	) );
	

	/* Our Team Member Section Category */
	$wp_customize->add_setting( 'unicon_team_team_id', array(
        'default' => '0',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( new Unicon_Customize_Category_Control( $wp_customize, 'unicon_team_team_id', array(
	    'label' => __( 'Select Our Team Section Category', 'unicon' ),
	    'description' => __( 'Select cateogry for Our Team Member Section', 'unicon' ),
	    'section' => 'unicon_team_section',
	) ) );


/* Video Section */
$wp_customize->add_section( 'unicon_video_section', array(
    'title'		=> __( 'Video Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 6,
) );

	$wp_customize->add_setting( 'unicon_vieo_section_option', array(
	    'default' => 'hide',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_vieo_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_video_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Video Section Title */
	$wp_customize->add_setting( 'unicon_video_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Video Main Title','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_video_section_main_title', array(
	    'label'    => __( 'Video Main Title', 'unicon' ),
	    'section'  => 'unicon_video_section',
	    'settings' => 'unicon_video_section_main_title'
	) );

	/* Very Short Video Description */
	$wp_customize->add_setting( 'unicon_video_description', array(
	    'sanitize_callback' => 'wp_filter_nohtml_kses',
	    'transport' => 'postMessage',
	    'default' => __('Better security happens when we work together, Get tips on further steps you can take to protect yourself online.','unicon'),
	) );

	$wp_customize->add_control( 'unicon_video_description', array(
	    'type' => 'textarea',
	    'label'    => __( 'Very Short Video Description', 'unicon' ),
	    'section'  => 'unicon_video_section',
	    'settings' => 'unicon_video_description'
	) );

	/* Video Section Title */
	$wp_customize->add_setting( 'unicon_video_section_url', array(
	    'sanitize_callback' => 'esc_url_raw',
	    'default' => '',
	) );

	$wp_customize->add_control( 'unicon_video_section_url', array(
	    'label'    => __( 'Enter the Youtube Video url', 'unicon' ),
	    'section'  => 'unicon_video_section',
	    'settings' => 'unicon_video_section_url'
	) );


/* Our Works Section */
$wp_customize->add_section( 'unicon_works_section', array(
    'title'		=> __( 'Our Works Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 7,
) );

	$wp_customize->add_setting( 'unicon_works_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_works_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_works_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Our Works Main Section Title */
	$wp_customize->add_setting( 'unicon_works_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Our Works','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_works_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_works_section',
	    'settings' => 'unicon_works_section_main_title'
	) );

	/* Our Works Sub Section Title */
	$wp_customize->add_setting( 'unicon_works_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Project Done','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_works_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_works_section',
	    'settings' => 'unicon_works_section_sub_title'
	) );
	

	/* Our Works Section Category */
	$wp_customize->add_setting( 'unicon_works_team_id', array(
        'default' => '0',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( new Unicon_Customize_Category_Control( $wp_customize, 'unicon_works_team_id', array(
	    'label' => __( 'Select Our Works Section Category', 'unicon' ),
	    'description' => __( 'Select cateogry for Our Works Section', 'unicon' ),
	    'section' => 'unicon_works_section',
	) ) );



/* Call To Action Section */
$wp_customize->add_section( 'unicon_call_to_action_section', array(
    'title'		=> __( 'Call To Action Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 8,
) );

	$wp_customize->add_setting( 'unicon_call_to_action_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_call_to_action_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_call_to_action_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );


	$wp_customize->add_setting( 'unicon_call_to_action_bg' , array(
	    'default'       =>      '',
	    'sanitize_callback' => 'esc_url_raw',  // done
	    'transport' => 'postMessage'
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'unicon_call_to_action_bg', array(
	    'section'    =>      'unicon_call_to_action_section',
	    'label'      =>      __('Upload Call To Action Bg Image', 'unicon'),
	    'type'       =>      'image',
	) ) );

	/* Call To Action Main Section Title */
	$wp_customize->add_setting( 'unicon_call_to_action_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Unicon WordPress Theme','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_call_to_action_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_call_to_action_section',
	    'settings' => 'unicon_call_to_action_section_main_title'
	) );


	/* Call To Action Very Short Description */
	$wp_customize->add_setting( 'unicon_call_to_action_description', array(
	    'sanitize_callback' => 'wp_filter_nohtml_kses',
	    'default' => __('Build promptly, Launch Fast','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_call_to_action_description', array(
	    'type' => 'textarea',
	    'label'    => __( 'Very Short Description', 'unicon' ),
	    'section'  => 'unicon_call_to_action_section',
	    'settings' => 'unicon_call_to_action_description'
	) );

	/* First button */
	$wp_customize->add_setting( 'unicon_call_to_action_first_button_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Read More','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_call_to_action_first_button_title', array(
	    'label'    => __( 'First button label', 'unicon' ),
	    'section'  => 'unicon_call_to_action_section',
	    'settings' => 'unicon_call_to_action_first_button_title'
	) );

	$wp_customize->add_setting( 'unicon_call_to_action_first_button_url', array(
	    'sanitize_callback' => 'esc_url_raw',
	    'default' => esc_url( home_url( '/' ) ).'#focus',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_call_to_action_first_button_url', array(
	    'label'    => __( 'First button link', 'unicon' ),
	    'section'  => 'unicon_call_to_action_section',
	    'settingsd' => 'unicon_call_to_action_first_button_url',
	) );

	/* Second button */
	$wp_customize->add_setting( 'unicon_call_to_action_second_button_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Purchase Now','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_call_to_action_second_button_title', array(
	    'label'    => __( 'Second button label', 'unicon' ),
	    'section'  => 'unicon_call_to_action_section',
	    'settings' => 'unicon_call_to_action_second_button_title'
	) );

	$wp_customize->add_setting( 'unicon_call_to_action_second_button_url', array(
	    'sanitize_callback' => 'esc_url_raw',
	    'default' => esc_url( home_url( '/' ) ).'#focus',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_call_to_action_second_button_url', array(
	    'label'    => __( 'Second button link', 'unicon' ),
	    'section'  => 'unicon_call_to_action_section',
	    'settingsd' => 'unicon_call_to_action_second_button_url',
	) );


/* Testimonial Section */
$wp_customize->add_section( 'unicon_testimonial_section', array(
    'title'		=> __( 'Testimonial Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 9,
) );

	$wp_customize->add_setting( 'unicon_testimonial_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_testimonial_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_testimonial_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Testimonial Main Section Title */
	$wp_customize->add_setting( 'unicon_testimonial_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Clients','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_testimonial_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_testimonial_section',
	    'settings' => 'unicon_testimonial_section_main_title'
	) );

	/* Testimonial Sub Section Title */
	$wp_customize->add_setting( 'unicon_testimonial_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Testimonials','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_testimonial_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_testimonial_section',
	    'settings' => 'unicon_testimonial_section_sub_title'
	) );
	

	/* Testimonial Section Category */
	$wp_customize->add_setting( 'unicon_testimonial_team_id', array(
        'default' => '0',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( new Unicon_Customize_Category_Control( $wp_customize, 'unicon_testimonial_team_id', array(
	    'label' => __( 'Select Testimonial Section Category', 'unicon' ),
	    'description' => __( 'Select cateogry for Testimonial Section', 'unicon' ),
	    'section' => 'unicon_testimonial_section',
	) ) );


/* Our Partners Section */
$wp_customize->add_section( 'unicon_our_partners_section', array(
    'title'		=> __( 'Our Partners Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 10,
) );

	$wp_customize->add_setting( 'unicon_our_partners_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_our_partners_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_our_partners_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Our Partners Full Background Image */
	$wp_customize->add_setting( 'unicon_our_partners_bg_image', array(
	    'default'       =>      '',
	    'sanitize_callback' => 'esc_url_raw',  // done
	    'transport' => 'postMessage'
	) );
	
	$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'unicon_our_partners_bg_image', array(
	    'section'    =>      'unicon_our_partners_section',
	    'label'      =>      __('Upload Partners Bg Image', 'unicon'),
	    'type'       =>      'image',
	) ) );
	

	/* Our Partners Main Section Title */
	$wp_customize->add_setting( 'unicon_our_partners_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Our Partners','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_our_partners_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_our_partners_section',
	    'settings' => 'unicon_our_partners_section_main_title'
	) );

	/* Our Partners Very Short Description */
	$wp_customize->add_setting( 'unicon_our_partners_description', array(
	    'sanitize_callback' => 'wp_filter_nohtml_kses',
	    'transport' => 'postMessage',
	    'default' => __('Better security happens when we work together, Get tips on further steps you can take to protect yourself online.','unicon'),
	) );

	$wp_customize->add_control( 'unicon_our_partners_description', array(
	    'type' => 'textarea',
	    'label'    => __( 'Very Short Description', 'unicon' ),
	    'section'  => 'unicon_our_partners_section',
	    'settings' => 'unicon_our_partners_description'
	) );

	/* Our Partners Logo */
	for ( $count = 1; $count <= 5; $count++ ) {

		$wp_customize->add_setting( 'unicon_our_partners_logo_'.$count , array(
		    'default'       =>      '',
		    'transport' => 'postMessage',
		    'sanitize_callback' => 'esc_url_raw'  // done
		) );
		
		$wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'unicon_our_partners_logo_'.$count , array(
		    'section'    =>      'unicon_our_partners_section',
		    'label'      =>      __('Upload Partners Logo', 'unicon'),
		    'type'       =>      'image',
		) ) );

	}


/* Our Blogs Section */
$wp_customize->add_section( 'unicon_blog_section', array(
    'title'		=> __( 'Our Blogs Section', 'unicon' ),
    'panel'     => 'unicon-homepage-panel',
    'priority'  => 11,
) );

	$wp_customize->add_setting( 'unicon_blog_section_option', array(
	    'default' => 'show',
	    'sanitize_callback' => 'unicon_sanitize_switch_option',
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( new Unicon_Customize_Switch_Control( $wp_customize, 'unicon_blog_section_option',  array(
	    'type'      => 'switch',                    
	    'label'     => __( 'Enable / Disable Option', 'unicon' ),
	    'section'   => 'unicon_blog_section',
	    'choices'   => array(
		        'show'  => __( 'Enable', 'unicon' ),
		        'hide'  => __( 'Disable', 'unicon' )
	        )
	) ) );

	/* Our Blogs Main Section Title */
	$wp_customize->add_setting( 'unicon_blog_section_main_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Recent','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_blog_section_main_title', array(
	    'label'    => __( 'Main Title', 'unicon' ),
	    'section'  => 'unicon_blog_section',
	    'settings' => 'unicon_blog_section_main_title'
	) );

	/* Our Blogs Sub Section Title */
	$wp_customize->add_setting( 'unicon_blog_section_sub_title', array(
	    'sanitize_callback' => 'unicon_text_sanitize',
	    'default' => __('Blogs','unicon'),
	    'transport' => 'postMessage'
	) );

	$wp_customize->add_control( 'unicon_blog_section_sub_title', array(
	    'label'    => __( 'Sub Title', 'unicon' ),
	    'section'  => 'unicon_blog_section',
	    'settings' => 'unicon_blog_section_sub_title'
	) );

	/* Blogs Section Category */
	$wp_customize->add_setting( 'unicon_blog_team_id', array(
        'default' => '0',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint'
	) );

	$wp_customize->add_control( new Unicon_Customize_Category_Control( $wp_customize, 'unicon_blog_team_id', array(
	    'label' => __( 'Select Blog Section Category', 'unicon' ),
	    'description' => __( 'Select cateogry for Blog Section', 'unicon' ),
	    'section' => 'unicon_blog_section',
	) ) );

	/* Our Blogs Display Number of Posts */
	$wp_customize->add_setting( 'unicon_blog_display_post_number', array(
		'default'           => '5',
		'sanitize_callback' => 'absint'
	) );	

	$wp_customize->add_control( 'unicon_blog_display_post_number', array(
		'label'    => __( 'Enter the Display Number Posts', 'unicon' ),
		'section'  => 'unicon_blog_section',
		'type'     => 'number'
	) );