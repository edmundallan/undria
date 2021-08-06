<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/** Add button that links to customise Element page */
add_action( 'post_submitbox_misc_actions', 'elementary_customize_element_button', 20 );
// add_action( 'post_submitbox_start', 'elementary_customize_element_button');

function elementary_customize_element_button() {
    global $post;
    $screen = get_current_screen();
    if ( get_post_type( $post ) === 'pauple_element' ) {
        $nonce = wp_create_nonce  ('customize_element');
        ?>
        <div class="misc-pub-section customize-element">
        <a class="preview button" href=<?php echo admin_url( 'admin.php?page=elementary' ); ?>&post=<?php echo get_the_id(); ?>&_wpnonce=<?php echo esc_attr( $nonce ); ?> target="_blank" id="customize_element"><?php esc_html_e( 'Customize this Element!', '' ); ?></a>
        </div>
        <?php
    }
}
