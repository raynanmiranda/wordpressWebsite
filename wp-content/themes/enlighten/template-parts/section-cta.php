<?php
$enlighten_cta_section_title = get_theme_mod('enlighten_cta_section_title');
$enlighten_cta_title = get_theme_mod('enlighten_cta_title');
$enlighten_cta_description = get_theme_mod('enlighten_cta_description');
$enlighten_cta_button_text = get_theme_mod('enlighten_cta_button_text');
$enlighten_cta_buttom_link = get_theme_mod('enlighten_button_link');
?>
<div class="ak-container">
    <div class="cta_wrap wow fadeInUp">
        <?php if($enlighten_cta_section_title){ ?>
            <span class="title_section_cta"><?php echo esc_attr($enlighten_cta_section_title); ?></span>
        <?php } ?>
        <?php if($enlighten_cta_description || $enlighten_cta_title){ ?>
            <div class="title_description">
                <?php if($enlighten_cta_title){ ?>
                    <span class="cta_title"><?php echo esc_attr($enlighten_cta_title); ?></span>
                <?php }
                if($enlighten_cta_description){ ?>
                    <span class="cta_desc"><?php echo wp_kses_post($enlighten_cta_description); ?></span>
                <?php } ?>
            </div>
        <?php } ?>
        <?php if($enlighten_cta_buttom_link){ ?>
            <span class="button_cta"><a target="_blank" class="cta_button" href="<?php echo esc_url($enlighten_cta_buttom_link); ?>"><?php echo esc_attr($enlighten_cta_button_text); ?></a></span>
        <?php } ?>
    </div>
</div>