<?php
/*
Plugin Name: BNS Bio
Plugin URI: http://buynowshop.com/plugins/bns-bio/
Description: An author details shortcode producing plugin with extensions
Version: 0.1
Text Domain: bns-bio
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Bio
 * An author details shortcode producing plugin with extensions
 *
 * @package     BNS_Bio
 * @link        http://buynowshop.com/plugins/bns-bio/
 * @link        https://github.com/Cais/bns-bio/
 * @link        http://wordpress.org/extend/plugins/bns-bio/
 * @version     0.1
 * @author      Edward Caissie <edward.caissie@gmail.com>
 * @copyright   Copyright (c) 2012, Edward Caissie
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.
 *
 * You may NOT assume that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details
 *
 * You should have received a copy of the GNU General Public License along with
 * this program; if not, write to:
 *
 *      Free Software Foundation, Inc.
 *      51 Franklin St, Fifth Floor
 *      Boston, MA  02110-1301  USA
 *
 * The license for this software can also likely be found here:
 * http://www.gnu.org/licenses/gpl-2.0.html
 */

class BNS_Bio {
    /** Constructor */
    function __construct(){
        /** Add Scripts and Styles */
        add_action( 'wp_enqueue_scripts', array( $this, 'Scripts_and_Styles' ) );

        /** Create Shortcode */
        add_shortcode( 'bns_bio', array( $this, 'author_block' ) );
    }

    /**
     * Enqueue Plugin Scripts and Styles
     * Adds plugin stylesheet and allows for custom stylesheet to be added by end-user.
     *
     * @package BNS_Bio
     * @since   0.1
     *
     * @uses    plugin_dir_path
     * @uses    plugin_dir_url
     * @uses    wp_enqueue_style
     */
    function Scripts_and_Styles() {
        /** Get the plugin data */
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $bns_bio_data = get_plugin_data( __FILE__ );

        /** Enqueue Styles */
        wp_enqueue_style( 'BNS-Bio-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-style.css', array(), $bns_bio_data['Version'], 'screen' );
        /** Check if custom stylesheet is readable (exists) */
        if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-bio-custom-style.css' ) ) {
            wp_enqueue_style( 'BNS-Bio-Custom-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-custom-style.css', array(), $bns_bio_data['Version'], 'screen' );
        }
    }

    /**
     * Author Block
     * Gets the author details and builds the basic structures of the output
     *
     * @package BNS_Bio
     * @since   0.1
     *
     * @uses    apply_filters
     * @uses    do_action
     * @uses    get_query_var
     * @uses    get_the_author_meta
     * @uses    get_user_by
     * @uses    get_userdata
     *
     */
    function author_block(  ){
        /** @var $current_author - current author data an as object */
        $current_author = ( get_query_var( 'author_name ' ) ) ? get_user_by( 'id', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );

        /** Get the various details to be displayed */
        $author_name    = get_the_author_meta( 'display_name', $current_author );
        $author_url     = get_the_author_meta( 'user_url', $current_author );
        $author_email   = get_the_author_meta( 'user_email', $current_author );
        $author_bio     = get_the_author_meta( 'user_description', $current_author );

        /** Start output buffer */
        ob_start();

        do_action( 'bns_bio_before_all' );

        do_action( 'bns_bio_before_author_name' );
        echo apply_filters( 'bns_bio_author_name_text', __( 'Written by ', 'bns-bio' ) ) . apply_filters( 'bns_bio_author_name', $author_name . '<br />' );
        do_action( 'bns_bio_after_author_name' );

        do_action( 'bns_bio_before_author_url' );
        echo apply_filters( 'bns_bio_author_url_text', __( 'From: ', 'bns-bio' ) ) . apply_filters( 'bns_bio_author_url', $author_url . '<br />' );
        do_action( 'bns_bio_after_author_url' );

        do_action( 'bns_bio_before_author_email' );
        echo apply_filters( 'bns_bio_author_name_email_text', __( 'Email: ', 'bns-bio' ) ) . apply_filters( 'bns_bio_author_email', $author_email . '<br />' );
        do_action( 'bns_bio_after_author_email' );

        do_action( 'bns_bio_before_author_bio' );
        echo apply_filters( 'bns_bio_author_bio_text', __( 'About: ', 'bns-bio' ) ) . apply_filters( 'bns_bio_author_bio', $author_bio );
        do_action( 'bns_bio_after_author_bio' );

        do_action( 'bns_bio_after_all' );

        /** @var $output - save output and close buffer */
        $output = ob_get_clean();

        return $output;
    }
}
$bns_bio = new BNS_Bio();