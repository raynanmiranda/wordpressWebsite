<?php
/**
 * The template for displaying all pages.
 * Template Name: Team
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package Revolve
 */

get_header(); ?>

    <div class="revole-page-header clearfix">

        <?php while ( have_posts() ) : the_post(); ?>

		<?php $revolve_parid = get_the_ID(); ?>

        <h1 class="revolve-page-header-left">
           <span> <?php the_title(); ?></span>
        </h1>

        <div class="revolve-page-content-right">
            <?php the_content(); ?>
        </div>

        <?php endwhile; ?>
            
    </div>
    
    <?php
        $revolve_team_cat = absint(get_theme_mod( 'revolve_team_category' ));
        $revolve_args = array(
        	'post_status' => 'publish',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'cat' => $revolve_team_cat
        );
        
        $revolve_query = new WP_Query($revolve_args);
        $revolve_post_arr = $revolve_query->get_posts();
        $revolve_post_arr2 = revolve_group_post_alphabetically($revolve_post_arr);
        $revolve_post_stacks = array_chunk($revolve_post_arr, 5);
        $revolve_post_stacks2 = array_chunk($revolve_post_arr, 2);
        $revolve_post_stacks3 = array_chunk($revolve_post_arr2, 4, true);
    ?>
    
    <!-- Team Controls -->
    <div id="team-control">
        <div class="team-lays">
            <div id="format-grid-1" class="format-grid team-disp-style">
                <a href="javascript:void(0);" class="gridlist icon-active" data-format="grid">
                <i class="fa fa-th"></i>
                </a>
            </div>
            
            <div id="format-tile-2" class="format-tile team-disp-style">
                <a href="javascript:void(0);" class="tilelist" data-format="tile">
                <i class="fa fa-list-ul"></i>
                </a>
            </div>
            
            <div id="format-list-3" class="format-list team-disp-style">
                <a href="javascript:void(0);" class="list" data-format="list">
                <i class="fa fa-align-justify"></i>
                </a>
            </div>
        </div>
        <div class="team-search">
            <input class="team-search-box" type="text" placeholder="<?php _e('Mr. Team Member', 'revolve'); ?>" />
            <i id="search-icon" class="fa fa-search"></i>
        </div>
    </div>
    
    <!-- Team Posts -->
    <?php if(!empty($revolve_post_arr)) : ?>
        <div class="team-grid-layout team-all-members">
            <?php foreach($revolve_post_stacks as $revolve_post_stack) : ?>
                <div class='team-list-block'>
                    <?php foreach($revolve_post_stack as $revolve_team) : ?>
                        <?php
                            if(has_post_thumbnail($revolve_team->ID)) {
                                $revolve_img = wp_get_attachment_image_src(get_post_thumbnail_id($revolve_team->ID), 'revolve-team-grid-thumb');
                                $revolve_img_src = $revolve_img[0];    
                            } else {
                                $revolve_img_src = get_template_directory_uri().'/images/no-team-member.jpg';
                            }
                            
                        ?>
                        <div class="team-image-wrap team-member" >
                            <div class="team-image"><img src="<?php echo esc_url($revolve_img_src); ?>" /></div>
                            <h2 class="team-member-name"><?php echo $revolve_team->post_title; ?></h2>
                        </div>
                        <div class="team-info" style="display: none;">
                            <span class="close-team-info"><i class="fa fa-times" aria-hidden="true"></i></span>
                            <div class="team-inner-info">
                                <div class="bio">
                                    <h2><?php echo $revolve_team->post_title; ?></h2>
                                    <hr />
                                   
                                        <?php echo $revolve_team->post_content; ?>
                                </div>
                                <div class="team-inner-image">
                                    <img src="<?php echo esc_url($revolve_img_src); ?>" />
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div id="team-infos-block" class="team-info-block" style="display: none;"></div>
            <?php endforeach; ?>
        </div>
        
        <div class="team-all-members team-tile-layout" style="display: none;">
            <?php foreach($revolve_post_stacks2 as $revolve_post_stack) : ?>
                <div class='team-list-block'>
                    <?php foreach($revolve_post_stack as $revolve_team) : ?>
                        <?php
                            if(has_post_thumbnail($revolve_team->ID)) {
                                $revolve_img = wp_get_attachment_image_src(get_post_thumbnail_id($revolve_team->ID), 'full');
                                $revolve_img_src = $revolve_img[0];    
                            } else {
                                $revolve_img_src = get_template_directory_uri().'/images/no-team-member.jpg';
                            }
                        ?>
                        <div class="team-image-wrap team-member">
                            <div class="team-image-wrap-member">
	                            <div class="team-image"><img src="<?php echo esc_url($revolve_img_src); ?>" /></div>
	                            <h2 class="team-member-name"><?php echo $revolve_team->post_title; ?></h2>
                            </div>
                        </div>
                        <div class="team-info" style="display: none;">
                            <span class="close-team-info"><i class="fa fa-times" aria-hidden="true"></i></span>
                            <div class="team-inner-info">
                            	<div class="bio">
                                    <h2><?php echo $revolve_team->post_title; ?></h2>
                                    <hr />
                                   
                                        <?php echo $revolve_team->post_content; ?>
                                </div>
                                <div class="team-inner-image">
                                    <img src="<?php echo esc_url($revolve_img_src); ?>" />
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="team-info-block" style="display: none;"></div>
            <?php endforeach; ?>
        </div>
        
        <div class="team-all-members team-list-layout" style="display: none;">
            <?php foreach($revolve_post_stacks3 as $revolve_post_stack) : ?>
                <div class='team-list-block'>
                    <?php foreach($revolve_post_stack as $revolve_alpha => $revolve_teams) : ?>
                    	<div class="letter-group">
                            <h2><?php echo $revolve_alpha; ?></h2>
                            <?php foreach($revolve_teams as $revolve_team) : ?>
                                <?php
                                    if(has_post_thumbnail($revolve_team->ID)) {
                                        $revolve_img = wp_get_attachment_image_src(get_post_thumbnail_id($revolve_team->ID), 'full');
                                        $revolve_img_src = $revolve_img[0];    
                                    } else {
                                        $revolve_img_src = get_template_directory_uri().'/images/no-team-member.jpg';
                                    }
                                    
                                ?>
                                <div class="team-image-wrap team-member">
                                    <h2 class="team-member-name"><?php echo $revolve_team->post_title; ?></h2>
                                </div>
                                <div class="team-info" style="display: none;">
                                    <span class="close-team-info"><i class="fa fa-times" aria-hidden="true"></i></span>
                                    <div class="team-inner-info">
                                    <div class="bio">
                                        <h2><?php echo $revolve_team->post_title; ?></h2>
                                        <hr />
                                        <?php echo $revolve_team->post_content; ?>
                                    </div>
                                    <div class="team-inner-image">
                                       <div class="team-image"><img src="<?php echo esc_url($revolve_img_src); ?>" /></div>
                                    </div>
                                    </div>
                                </div>
                            <?php endforeach; ?> <!-- Team Loop -->
                        </div> <!-- Team Group -->
                    <?php endforeach; ?>
                </div>
                <div class="team-info-block" style="display: none;"></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php get_footer(); ?>