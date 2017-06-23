<?php
/**
 * Feature Post
 *
 * @package Revolve
 */
/**
 * Adds Feature Post Widget.
 */
add_action('widgets_init', 'revolve_register_feature_post_widget');

function revolve_register_feature_post_widget() {
    register_widget('revolve_feature_post');
}

class Revolve_Feature_Post extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'revolve_feature_post', 'AP : Feature Post', array(
            'description' => __('A widget that Shows Feature Post', 'revolve')
            )
        );
    }

    /**
     * Helper function that holds widget fields
     * Array is used in update and form functions
     */
    private function widget_fields() {
        $fields = array(
            'feature_post1' => array(
                'revolve_widgets_name' => 'feature_post1',
                'revolve_widgets_title' => __('Feature Post 1', 'revolve'),
                'revolve_widgets_field_type' => 'selectpost',
            ),
            'feature_post2' => array(
                'revolve_widgets_name' => 'feature_post2',
                'revolve_widgets_title' => __('Feature Post 2', 'revolve'),
                'revolve_widgets_field_type' => 'selectpost',
            ),
            'feature_post3' => array(
                'revolve_widgets_name' => 'feature_post3',
                'revolve_widgets_title' => __('Feature Post 3', 'revolve'),
                'revolve_widgets_field_type' => 'selectpost',
            )
        );

        return $fields;
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget($args, $instance) {
        extract($args);
        
        $feature_post1 = isset($instance['feature_post1']) ? absint($instance['feature_post1']) : '';
        $feature_post2 = isset($instance['feature_post2']) ? absint($instance['feature_post2']) : '';
        $feature_post3 = isset($instance['feature_post3']) ? absint($instance['feature_post3']) : '';
        
        $fargs = array(
            'post__in' => array($feature_post1, $feature_post2, $feature_post3),
            'post_status' => 'publish',
            'posts_per_page' => 3,
        );
        
        $fquery = new WP_Query($fargs);
        
        if($fquery->have_posts()) :
        echo $before_widget;
        ?>
        <?php while($fquery->have_posts()) : $fquery->the_post(); ?>
            <div class="hm-feat-post-wrap">
                <div class="wd-pub-date"><?php echo get_the_date( get_option( 'date_format' ), get_the_ID() ); ?></div>
                <a href="<?php the_permalink(); ?>">
                    <h2 class="wd-feat-post-title"><?php the_title(); ?></h2>
                </a>
            </div>
        <?php endwhile; ?>
        <?php
        echo $after_widget;
        endif;
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @see WP_Widget::update()
         *
         * @param	array	$new_instance	Values just sent to be saved.
         * @param	array	$old_instance	Previously saved values from database.
         *
         * @uses	revolve_widgets_updated_field_value()		defined in widget-fields.php
         *
         * @return	array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance) {
            $instance = $old_instance;

            $widget_fields = $this->widget_fields();

            // Loop through fields
            foreach ($widget_fields as $widget_field) {

                extract($widget_field);

                // Use helper function to get updated field values
                $instance[$revolve_widgets_name] = revolve_widgets_updated_field_value($widget_field, $new_instance[$revolve_widgets_name]);
            }

            return $instance;
        }

        /**
         * Back-end widget form.
         *
         * @see WP_Widget::form()
         *
         * @param	array $instance Previously saved values from database.
         *
         * @uses	revolve_widgets_show_widget_field()		defined in widget-fields.php
         */
        public function form($instance) {
            $widget_fields = $this->widget_fields();

            // Loop through fields
            foreach ($widget_fields as $widget_field) {

                // Make array elements available as variables
                extract($widget_field);
                $revolve_widgets_field_value = isset($instance[$revolve_widgets_name]) ? esc_attr($instance[$revolve_widgets_name]) : '';
                revolve_widgets_show_widget_field($this, $widget_field, $revolve_widgets_field_value);
            }
        }

    }
    