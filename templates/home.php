<?php
/**
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 * Template Name: Home
 */

$context = Timber::get_context();
$post = new TimberPost();
$context['post'] = $post;
Timber::render( 'home.twig', $context );