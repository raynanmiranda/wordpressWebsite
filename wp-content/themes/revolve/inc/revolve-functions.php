<?php
    /** Enqueue Styles and Scripts for Backend **/
    add_action( 'admin_enqueue_scripts', 'revolve_admin_enqueue_scripts' );
    
    function revolve_admin_enqueue_scripts() {
         /** Styles **/
        wp_enqueue_style( 'revolve-admin-custom-css', get_template_directory_uri() . '/inc/admin/css/custom-admin.css');
        
        /** Scripts **/
        wp_enqueue_script( 'revolve-custom-js', get_template_directory_uri(). '/inc/admin/js/custom-admin.js', array('jquery') );
        
        /** Loads the media js file in Page Edit Page only **/
        $currentScreen = get_current_screen();
        if( $currentScreen->id == "page" ) {
            wp_enqueue_media();
            wp_enqueue_script( 'revolve-media-uploader-js', get_template_directory_uri(). '/inc/admin/js/media-uploader.js', array('jquery') );
        }
    }
    
    /** Function to Check if Page has Child pages or not **/
    function revolve_has_children($post_id) {
        $children = get_pages('child_of=$post_id');
        if( count( $children ) != 0 ) {
            return true;
        } // Has Children
        return false; // No children
    }
    
    /** Function to get the home sliders **/
    function revolve_get_home_slider() {
        $hsliders = array();
        
        /** Slide 1 **/
        $slide1 = absint(get_theme_mod( 'revolve_slide1', 0 ));
        if($slide1) {
            $sl1_cap_title = get_theme_mod( 'revolve_slide1_caption_title', 'Caption Title' );
            $sl1_cap_content = get_theme_mod( 'revolve_slide1_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide1));
            $sl1_id = $pst->post_name;
            $sl1_widget = get_theme_mod( 'revolve_slide1_widget', 0 );
             
            $hsliders[] = array(
                $slide1,
                $sl1_cap_title,
                $sl1_cap_content,
                $sl1_id,
                $sl1_widget
            );
        }
        
        /** Slide 2 **/
        $slide2 = absint(get_theme_mod( 'revolve_slide2', 0 ));
        if($slide2) {
            $sl2_cap_title = get_theme_mod( 'revolve_slide2_caption_title', 'Caption Title' );
            $sl2_cap_content = get_theme_mod( 'revolve_slide2_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide2));
            $sl2_id = $pst->post_name;
            $sl2_widget = get_theme_mod( 'revolve_slide2_widget', 0 ); 
             
            $hsliders[] = array(
                $slide2,
                $sl2_cap_title,
                $sl2_cap_content,
                $sl2_id,
                $sl2_widget,
            );
        }
        
        /** Slide 3 **/
        $slide3 = absint(get_theme_mod( 'revolve_slide3', 0 ));
        if($slide3) {
            $sl3_cap_title = get_theme_mod( 'revolve_slide3_caption_title', 'Caption Title' );
            $sl3_cap_content = get_theme_mod( 'revolve_slide3_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide3));
            $sl3_id = $pst->post_name;
            $sl3_widget = get_theme_mod( 'revolve_slide3_widget', 0 ); 
             
            $hsliders[] = array(
                $slide3,
                $sl3_cap_title,
                $sl3_cap_content,
                $sl3_id,
                $sl3_widget,
            );
        }
        
        /** Slide 4 **/
        $slide4 = absint(get_theme_mod( 'revolve_slide4', 0 ));
        if($slide4) {
            $sl4_cap_title = get_theme_mod( 'revolve_slide4_caption_title', 'Caption Title' );
            $sl4_cap_content = get_theme_mod( 'revolve_slide4_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide4));
            $sl4_id = $pst->post_name;
            $sl4_widget = get_theme_mod( 'revolve_slide4_widget', 0 ); 
             
            $hsliders[] = array(
                $slide4,
                $sl4_cap_title,
                $sl4_cap_content,
                $sl4_id,
                $sl4_widget,
            );
        }
        
        /** Slide 5 **/
        $slide5 = absint(get_theme_mod( 'revolve_slide5', 0 ));
        if($slide5) {
            $sl5_cap_title = get_theme_mod( 'revolve_slide5_caption_title', 'Caption Title' );
            $sl5_cap_content = get_theme_mod( 'revolve_slide5_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide5));
            $sl5_id = $pst->post_name;
            $sl5_widget = get_theme_mod( 'revolve_slide5_widget', 0 ); 
             
            $hsliders[] = array(
                $slide5,
                $sl5_cap_title,
                $sl5_cap_content,
                $sl5_id,
                $sl5_widget
            );
        }
        
        /** Slide 6 **/
        $slide6 = absint(get_theme_mod( 'revolve_slide6', 0 ));
        if($slide6) {
            $sl6_cap_title = get_theme_mod( 'revolve_slide6_caption_title', 'Caption Title' );
            $sl6_cap_content = get_theme_mod( 'revolve_slide6_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide6));
            $sl6_id = $pst->post_name;
            $sl6_widget = get_theme_mod( 'revolve_slide6_widget', 0 ); 
             
            $hsliders[] = array(
                $slide6,
                $sl6_cap_title,
                $sl6_cap_content,
                $sl6_id,
                $sl6_widget
            );
        }
        
        /** Slide 7 **/
        $slide7 = absint(get_theme_mod( 'revolve_slide7', 0 ));
        if($slide7) {
            $sl7_cap_title = get_theme_mod( 'revolve_slide7_caption_title', 'Caption Title' );
            $sl7_cap_content = get_theme_mod( 'revolve_slide7_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide7));
            $sl7_id = $pst->post_name;
            $sl7_widget = get_theme_mod( 'revolve_slide7_widget', 0 ); 
             
            $hsliders[] = array(
                $slide7,
                $sl7_cap_title,
                $sl7_cap_content,
                $sl7_id,
                $sl7_widget
            );
        }
        
        /** Slide 8 **/
        $slide8 = absint(get_theme_mod( 'revolve_slide8', 0 ));
        if($slide8) {
            $sl8_cap_title = get_theme_mod( 'revolve_slide8_caption_title', __('Caption Title', 'revolve') );
            $sl8_cap_content = get_theme_mod( 'revolve_slide8_caption_content', 'Caption Content' );
            $pst = get_post(esc_attr($slide8));
            $sl8_id = $pst->post_name;
            $sl8_widget = get_theme_mod( 'revolve_slide8_widget', 0 ); 
             
            $hsliders[] = array(
                $slide8,
                $sl8_cap_title,
                $sl8_cap_content,
                $sl8_id,
                $sl8_widget
            );
        }
        
        return $hsliders;
    }
    
    /** Blog Featured Post **/
    function revolve_feat_blog_post_ac() {
        $feat_blog_post = absint(get_theme_mod('revolve_blog_feat_post'));
        if($feat_blog_post) {
            $query = new WP_Query(array('p' => $feat_blog_post));
            while($query->have_posts()) {
                $query->the_post();
                
                $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
                $img = has_post_thumbnail(get_the_ID()) ? $image[0] : '';
                ?>
                <header class="blog-page-header"<?php if($img) : ?> style="background-image: url('<?php echo esc_url($img); ?>');"<?php endif; ?> >
                    <div class="title_section">
                        <div class="feat-post-pubdate"><?php echo get_the_date( get_option( 'date_format' ), get_the_ID() ); ?></div>
                        <div class="revolve-bar"></div>
                        <a href="<?php the_permalink(); ?>">
                            <h2 class="feat-post-title"><?php the_title(); ?></h2>
                        </a>
                    </div>
                </header>
                <?php
                wp_reset_postdata();
            }
        } else {
            
        }
    }
    
    add_action('revolve_feat_blog_post', 'revolve_feat_blog_post_ac');
    
    /** Post Excerpt **/
    function revolve_get_excerpt($content = '', $length = 200, $read_more = '') {
        $excerpt = '';
        global $post;
        $content = strip_tags(strip_shortcodes($content));
        if(strlen($content) >= $length){
            $pos = strpos( $content, ' ', $length);
            $excerpt .=  substr( $content, 0, $pos );
            
        } else{
            $excerpt .= $content;
        }        
        
        if($read_more != ''){
            $excerpt .= ' <a class="rev-read-more" href="'.get_the_permalink($post->ID).'">'.$read_more.'</a>';
        }
        
        return $excerpt; 
    }
    
    /** Group Posts Alphabetically **/
    function revolve_group_post_alphabetically($post_arr) {
        $arr = array();
        $tletter = 'A';
        
        if(!empty ($post_arr)) {
            foreach($post_arr as $pst) {
                $fletter = strtoupper(substr(trim($pst->post_title), 0, 1));
                if($fletter != $tletter) {
                    $tletter = $fletter;
                }
                
                if($fletter == $tletter) {
                   $arr[$tletter][] = $pst;
                }            
                
            }
        }
        
        return $arr;
    }
    
    /** Exclude Categories from Blog Page **/
    function revolve_exclude_cat_from_blog($query) {
        $revolve_blog_exclude_cat = sanitize_text_field(get_theme_mod('revolve_blog_exclude_cat'));
        
        $ex_cats = explode(',', $revolve_blog_exclude_cat);
        
        if ( $query->is_home() ) {
            $query->set('category__not_in', $ex_cats);
        }
        
        return $query;
    }
    add_filter('pre_get_posts', 'revolve_exclude_cat_from_blog');
?>