<?php
function enlighten_dynamic_styles(){
    $header_image = get_theme_mod('enlighten_header_banner_image');
    if($header_image){
        ?>
        <style>
.header-banner-container{
    background-image: url(<?php echo $header_image; ?>);
    background-repeat: no-repeat;
}
        
        </style>
        <?php
    }
    else{
        ?>
        <style>
.header-banner-container{
    background-image: url(<?php echo get_template_directory_uri(). '/images/banner.jpg' ?>);
    background-repeat: no-repeat;
}
        </style>
        <?php
    }
}
add_action('wp_head', 'enlighten_dynamic_styles', 100);