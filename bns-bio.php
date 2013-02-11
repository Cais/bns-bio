<?php
/*
Plugin Name: BNS Bio
Plugin URI: http://buynowshop.com/plugins/bns-bio/
Description: An author details shortcode plugin with extensions that modify output
Version: 0.3
Text Domain: bns-bio
Author: Edward Caissie
Author URI: http://edwardcaissie.com/
License: GNU General Public License v2
License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*/

/**
 * BNS Bio
 * An author details shortcode plugin with multiple extensions that can modify
 * the output. The extension plugins, using some of the many available hooks,
 * can add a rounded corner border; display the details as an unordered list;
 * and/or hide the author email address details.
 *
 * @package     BNS_Bio
 * @link        http://buynowshop.com/plugins/bns-bio/
 * @link        https://github.com/Cais/bns-bio/
 * @link        http://wordpress.org/extend/plugins/bns-bio/
 * @version     0.3
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
 *
 * @version 0.3
 * @date    February 11, 2013
 * Refactoring without functionality changes
 * Documentation updates
 * Added code block termination comments
 */

class BNS_Bio {

    /** Constructor */
    function __construct() {

        /** Add Scripts and Styles */
        add_action( 'wp_enqueue_scripts', array( $this, 'scripts_and_styles' ) );

        /** Create Shortcode */
        add_shortcode( 'bns_bio', array( $this, 'author_block' ) );

    } /** End function - construct */


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
    function scripts_and_styles() {

        /** Get the plugin data */
        require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        $bns_bio_data = get_plugin_data( __FILE__ );

        /** Enqueue Styles */
        wp_enqueue_style( 'BNS-Bio-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-style.css', array(), $bns_bio_data['Version'], 'screen' );
        /** Check if custom stylesheet is readable (exists) */
        if ( is_readable( plugin_dir_path( __FILE__ ) . 'bns-bio-custom-style.css' ) ) {
            wp_enqueue_style( 'BNS-Bio-Custom-Style', plugin_dir_url( __FILE__ ) . 'bns-bio-custom-style.css', array(), $bns_bio_data['Version'], 'screen' );
        } /** End if - is readable */

    } /** End function - scripts and styles */


    /**
     * Author Details
     * Collects the author details via a query then returns the specific value
     * based on what detail is passed to the function
     *
     * @package BNS_Bio
     * @since   0.3
     *
     * @param   $value - name|url|email|about
     *
     * @uses    get_query_var
     * @uses    get_the_author_meta
     * @uses    get_user_by
     * @uses    get_userdata
     *
     * @return  null|string - details as per value passed
     */
    function author_details( $value ) {
        /** @var $current_author - author object */
        $current_author = ( get_query_var( 'author_name ' ) ) ? get_user_by( 'id', get_query_var( 'author_name' ) ) : get_userdata( get_query_var( 'author' ) );

        /** Get the various details to be displayed */
        if ( 'name' == $value ) {
            return get_the_author_meta( 'display_name', $current_author );
        } elseif ( 'url' == $value ) {
            return get_the_author_meta( 'user_url',     $current_author );
        } elseif ( 'email' == $value ) {
            return get_the_author_meta( 'user_email',   $current_author );
        } elseif ( 'about' == $value ) {
            return get_the_author_meta( 'description',  $current_author );
        } else {
            return null;
        } /** End if - value */

    } /** End function - author details */


    /**
     * Author Block
     * Gets the author details and builds the basic structures of the output
     *
     * @package BNS_Bio
     * @since   0.1
     *
     * @uses    BNS_Bio::author_details
     * @uses    apply_filters
     * @uses    do_action
     *
     * @version 0.3
     * @date    February 11, 2013
     * Refactored to use `author_details` method
     */
    function author_block() {

        /** Start output */
        $output = '<div class="bns-bio">';

        ob_start();
        do_action( 'bns_bio_before_all' );
        $output .= ob_get_clean();

        ob_start();
        do_action( 'bns_bio_before_author_name' );
        $output .= ob_get_clean();
        $output .= apply_filters( 'bns_bio_author_name_text', sprintf( '<span class="bns-bio-author-name-text">%1$s</span>', __( 'Written by: ', 'bns-bio' ) ) )
                . apply_filters( 'bns_bio_author_name', sprintf( '<span class="bns-bio-author-name">%1$s</span>', $this->author_details( 'name' ) . '<br />' ) );
        do_action( 'bns_bio_after_author_name' );

        $author_url = $this->author_details( 'url' );
        if ( ! empty( $author_url ) ) {
            ob_start();
            do_action( 'bns_bio_before_author_url' );
            $output .= ob_get_clean();
            $output .= apply_filters( 'bns_bio_author_url_text', sprintf( '<span class="bns-bio-author-url-text">%1$s</span>', __( 'From: ', 'bns-bio' ) ) )
                    . apply_filters( 'bns_bio_author_url', sprintf( '<span class="bns-bio-author-url">%1$s</span>', $this->author_details( 'url' ) . '<br />' ) );
        } /** End if - not empty */

        ob_start();
        do_action( 'bns_bio_before_author_email' );
        $output .= ob_get_clean();
        $output .= apply_filters( 'bns_bio_author_email_text', sprintf( '<span class="bns-bio-author-email-text">%1$s</span>', __( 'Email: ', 'bns-bio' ) ) )
                . apply_filters( 'bns_bio_author_email', sprintf( '<span class="bns-bio-author-email">%1$s</span>', $this->author_details( 'email' ) . '<br />' ) );

        $author_bio = $this->author_details( 'about' );
        if ( ! empty( $author_bio ) ) {
            ob_start();
            do_action( 'bns_bio_before_author_desc' );
            $output .= ob_get_clean();
            $output .= apply_filters( 'bns_bio_author_desc_text', sprintf( '<span class="bns-bio-author-desc-text">%1$s</span>', __( 'About: ', 'bns-bio' ) ) )
                    . apply_filters( 'bns_bio_author_desc', sprintf( '<span class="bns-bio-author-desc">%1$s</span>', $this->author_details( 'about' ) ) );
        } /** End if - not empty */

        ob_start();
        do_action( 'bns_bio_after_all' );
        $output .= ob_get_clean();

        /** End output */
        $output .= '</div><!-- .bns-bio -->';

        return $output;

    } /** End function - author block */


} /** End class - BNS Bio */

/** @var $bns_bio - new instance of class */
$bns_bio = new BNS_Bio();