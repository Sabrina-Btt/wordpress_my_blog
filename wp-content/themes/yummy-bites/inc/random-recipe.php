<?php
/**
 * Functionality for the "Surpreenda-me" button.
 *
 * Redirects users to a random post when the `?random=1` parameter is present in the URL.
 */

function redirect_to_random_post() {
    if (isset($_GET['random'])) {
        $random_post = get_posts([
            'post_type'      => 'post',
            'orderby'        => 'rand',
            'posts_per_page' => 1,
        ]);

        if (!empty($random_post)) {
            wp_redirect(get_permalink($random_post[0]->ID));
            exit;
        }
    }
}
add_action('template_redirect', 'redirect_to_random_post');
