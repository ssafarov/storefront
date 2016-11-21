<?php
/**
 * Splitter
 */
function splitter_shortcode( $atts, $content = null ) {
	return '<div class="splitter"></div>';
}
add_shortcode('splitter', 'splitter_shortcode');

/**
 * Columns
 */
function two_third_shortcode($atts, $content = null) {
	return '<div class="col-md-8 col-xs-12">'.do_shortcode($content).'</div>';
}
add_shortcode('two third', 'two_third_shortcode');

function two_third_last_shortcode($atts, $content = null) {
	return '<div class="col-md-8 col-xs-12">'.do_shortcode($content).'</div>';
}
add_shortcode('two third last', 'two_third_last_shortcode');

function one_third_shortcode($atts, $content = null) {
	return '<div class="col-md-4 col-xs-12">'.do_shortcode($content).'</div>';
}
add_shortcode('one third', 'one_third_shortcode');

function one_third_last_shortcode($atts, $content = null) {
	return '<div class="col-md-4 col-xs-12">'.do_shortcode($content).'</div>';
}
add_shortcode('one third last', 'one_third_last_shortcode');

function one_full_shortcode($atts, $content = null) {
	return '<div class="col-xs-12">'.do_shortcode($content).'</div>';
}
add_shortcode('one full', 'one_full_shortcode');

function one_half_shortcode($atts, $content = null) {
	return '<div class="col-xs-12 col-md-6">'.do_shortcode($content).'</div>';
}
add_shortcode('one half', 'one_half_shortcode');

function one_half_last_shortcode($atts, $content = null) {
	return '<div class="col-xs-12 col-md-6">'.do_shortcode($content).'</div>';
}
add_shortcode('one half last', 'one_half_last_shortcode');

function one_quarter_shortcode($atts, $content = null) {
	return '<div class="col-xs-12 col-md-3">'.do_shortcode($content).'</div>';
}
add_shortcode('one quarter', 'one_quarter_shortcode');

function one_quarter_last_shortcode($atts, $content = null) {
	return '<div class="col-xs-12 col-md-3">'.do_shortcode($content).'</div>';
}
add_shortcode('one quarter last', 'one_quarter_last_shortcode');

/**
 * Boxes
 */
function box_shortcode($atts, $content = null) {
	return '<div class="box">'.do_shortcode($content).'</div>';
}
add_shortcode('box', 'box_shortcode');

// Portfolio testimonial
function portfolio_testimonial_shortcode($atts, $content = null) {
	return '</div><div class="portfolio-testimonial"><div class="boxed"><div class="testimonial">'.do_shortcode($content).'</div></div></div><div class="boxed">';
}
add_shortcode('portfolio testimonial', 'portfolio_testimonial_shortcode');

// Portfolio section
function portfolio_section_shortcode($atts, $content = null) {
	return '</div><div class="section"><div class="row">'.strip_tags(do_shortcode($content), '<div><a><img><h1><h2><h3><h4><h5><h6>').'</div></div><div class="boxed">';
}
add_shortcode('portfolio section', 'portfolio_section_shortcode');