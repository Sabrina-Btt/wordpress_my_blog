<?php
/**
 * Popular Recipes Widget
 *
 * @package Yummy Bites
 */

class Popular_Recipes_Widget extends WP_Widget {

    function __construct() {
        
        parent::__construct(
            'popular_recipes_widget', 
            __('Receitas Mais Populares', 'yummy_bites'), 
            array('description' => __('Exibe as receitas mais populares', 'yummy_bites')) 
        );
    }

    public function widget($args, $instance) {
        global $wpdb;

        echo $args['before_widget']; // Antes do widget

        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }

        $query = $wpdb->prepare(
            "
            SELECT p.ID, p.post_title, p.post_name, SUM(v.count) AS total_views
            FROM {$wpdb->posts} p
            INNER JOIN {$wpdb->prefix}post_views v ON p.ID = v.id
            WHERE p.post_type = 'post' 
            AND p.post_status = 'publish'
            AND v.type = %s
            GROUP BY p.ID
            ORDER BY total_views DESC
            LIMIT 3
            ",
            'general' 
        );
        
        $popular_recipes = $wpdb->get_results($query);

        if ($popular_recipes) {
            echo '<ul>'; 
            foreach ($popular_recipes as $recipe) {
                echo '<li><a href="' . esc_url(get_permalink($recipe->ID)) . '">' . esc_html($recipe->post_title) . '</a></li>';
            }
            echo '</ul>'; 
        } else {
            echo '<p>' . __('Nenhuma receita popular encontrada.', 'yummy_bites') . '</p>';
        }

        echo $args['after_widget']; 
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? sanitize_text_field($instance['title']) : __('Receitas Mais Populares', 'yummy_bites');
        ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Título:', 'yummy_bites'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = $old_instance;
        
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';

        return $instance;
    }
}

function register_popular_recipes_widget() {
    register_widget('Popular_Recipes_Widget');
}
add_action('widgets_init', 'register_popular_recipes_widget');
