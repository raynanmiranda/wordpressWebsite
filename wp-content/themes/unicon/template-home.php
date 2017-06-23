<?php
/**
 * The template for displaying all pages.
 *
 * Template Name: Front Page
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Unicon
 */

get_header(); 
	
	/**
	 * About Section in Front Page
	*/
	do_action( 'unicon_about_section' );

	/**
	 * About Section in Front Page
	*/
	do_action( 'unicon_services_section' );

	/**
	 * Sucess Graph Section in Front Page
	*/
	do_action( 'unicon_sucess_graph_section' );

	/**
	 * Counter Section in Front Page
	*/
	do_action( 'unicon_counter_section' );

	/**
	 * Team Section in Front Page
	*/
	do_action( 'unicon_team_section' );

	/**
	 * Video Section in Front Page
	*/
	do_action( 'unicon_video_section' );

	/**
	 * Our Works Section in Front Page
	*/
	do_action( 'unicon_works_section' );


	/**
	 * Call To Action Section in Front Page
	*/
	do_action( 'unicon_call_to_action_section' );

	/**
	 * Testimonial in Front Page
	*/
	do_action( 'unicon_testimonial_section' );

	/**
	 * Our Partners in Front Page
	*/
	do_action( 'unicon_partners_section' );

	/**
	 * Our Blog in Front Page
	*/
	do_action( 'unicon_blog_section' );		
	

get_footer();