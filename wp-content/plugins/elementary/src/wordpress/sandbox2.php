<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function pauple_elementary_sandbox2() {

    global $wordpress_functions;
	echo '<div class="wrap"><h1>Sandbox 2</h1></div>';

	// $b = elementary_button_field_data( 324, 'category' );
	// $wordpress_functions->print_array($b);
	//
	$b1 = elementary_button_field_data( 164, 'pa_color' );
	$wordpress_functions->print_array($b1);

	// $elementary_get_button_fields = elementary_get_button_fields(175);
	// $wordpress_functions->print_array($elementary_get_button_fields);

	$elementary_get_rating_count = elementary_wc_get_rating_count(164);
	echo "elementary_get_rating_count";
    $wordpress_functions->print_array($elementary_get_rating_count);
	$elementary_get_average_rating = elementary_wc_get_average_rating(164);
    echo "elementary_get_average_rating";
	$wordpress_functions->print_array($elementary_get_average_rating);

}
