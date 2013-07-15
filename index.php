<?php
/*
Plugin Name: Related Post Inside
Plugin URI: http://www.h2cweb.net/
Description: Related Post Inside plugin allows you to insert related posts inside of Posts. Related Post Inside plugin will make your website more SEO friendly, increase post views dramatically. Makes your blogging career more useful and very fast blogging.
Author: Md. Liton Arefin
Author URI: http://www.h2cweb.net
Version: 1.0

/*  Copyright 2013  Liton Arefin  (email : litonice09@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., A product of H2CWEB.NET, Dhaka, Bangladesh

http://www.gnu.org/licenses/gpl.html

*/

// Add a menu for our option page
add_action('admin_menu', 'h2cweb_rpi_add_menu');

function h2cweb_rpi_add_menu() {

    add_options_page( 'H2CWEB Related Post Inside', 'H2CWEB RPI', 'manage_options', 'h2cweb_rpi', 'h2cweb_rpi_option_page');

}

//Options menu page
function h2cweb_rpi_option_page() {
?>
    <div class="wrap">

      <?php screen_icon(); ?>

        <h2>H2CWEB Related Post Inside</h2>

        <form action="options.php" method="post">

            <?php settings_fields('h2cweb_rpi_options'); ?>

            <?php do_settings_sections('h2cweb_rpi'); ?>

            <input name="Submit" class="button button-primary" type="submit" value="Save Changes" />

        </form>

    </div>

    <?php

}


// Register and define the settings
add_action('admin_init', 'h2cweb_rpi_admin_init');

function h2cweb_rpi_admin_init()

{

    register_setting( 'h2cweb_rpi_options', 'h2cweb_rpi_options', 'h2cweb_rpi_validate_options' );

    add_settings_section( 'h2cweb_rpi_main', 'Related Post Inside Settings', 'h2cweb_rpi_section_text', 'h2cweb_rpi' );

    add_settings_field( 'h2cweb_rpi_title', 'Related Post Inside Title', 'h2cweb_rpi_setting_title', 'h2cweb_rpi', 'h2cweb_rpi_main' );

    add_settings_field( 'h2cweb_rpi_text_string', 'Number of posts to show', 'h2cweb_rpi_setting_input', 'h2cweb_rpi', 'h2cweb_rpi_main' );    

    add_settings_field( 'h2cweb_rpi_order', 'Order', 'h2cweb_rpi_order_setting', 'h2cweb_rpi', 'h2cweb_rpi_main' );

    add_settings_field( 'h2cweb_rpi_orderby', 'Order By', 'h2cweb_rpi_orderby_setting', 'h2cweb_rpi', 'h2cweb_rpi_main' );

}


// Draw the section header
function h2cweb_rpi_section_text() {
}

// Display and fill the form field
function h2cweb_rpi_setting_title() {

        // get option 'h2cweb_rpi_title' value from the database
        $options = get_option( 'h2cweb_rpi_options' );

        $h2cweb_rpi_title = $options['h2cweb_rpi_title'];

        // echo the field
        echo "<input type='text' id='h2cweb_rpi_title' style='width:200px;' name='h2cweb_rpi_options[h2cweb_rpi_title]' value='$h2cweb_rpi_title' />";

}

// Display and fill the form field
function h2cweb_rpi_setting_input() {

        // get option 'rpi_count' value from the database
        $options = get_option( 'h2cweb_rpi_options' );

        $rpi_count = $options['rpi_count'];

        // echo the field
        echo "<input type='number' style='width:200px;' id='rpi_count' name='h2cweb_rpi_options[rpi_count]' value='$rpi_count' />";
}
  
//Query Order Posts  
function h2cweb_rpi_order_setting() {

        // get option 'rpi_order' value from the database
        $options = get_option( 'h2cweb_rpi_options' );

        $rpi_order = $options['rpi_order'];
    
        $items = array("ASC","DESC");

        echo "<select id='rpi_order' name='h2cweb_rpi_options[rpi_order]' style='width:200px;'>";

        foreach($items as $item) {

        $selected = ($options['rpi_order']==$item) ? 'selected="selected"' : '';

        echo "<option value='$item' $selected>$item</option>";
    }

        echo "</select>";  

}

//Query Posts Orderby
function h2cweb_rpi_orderby_setting() {

        // get option 'rpi_orderby' value from the database
        $options = get_option( 'h2cweb_rpi_options' );

        $rpi_orderby = $options['rpi_orderby'];
    
        $items = array("ID","title","date","modified","author","post_name","rand");

        echo "<select id='rpi_orderby' name='h2cweb_rpi_options[rpi_orderby]' style='width:200px;'>";

        foreach($items as $item) {

        $selected = ($options['rpi_orderby']==$item) ? 'selected="selected"' : '';

        echo "<option value='$item' $selected>$item</option>";

    }

        echo "</select>";               
}
    
// Validate user input (we want text only)
function h2cweb_rpi_validate_options( $input ) {

        $valid = array();

        $valid['rpi_count'] = preg_replace('/[^0-9]/', '', $input['rpi_count'] );

        $valid['rpi_order'] = preg_replace('/[^a-zA-Z]/', '', $input['rpi_order'] );

        $valid['h2cweb_rpi_title'] = preg_replace('/[^a-z A-Z]/', '', $input['h2cweb_rpi_title'] );

        $valid['rpi_orderby'] = preg_replace('/[^a-zA-Z]/', '', $input['rpi_orderby'] );

        return $valid;

}



/* related posts by category */
function h2cweb_related_posts_shortcode($atts){

    $options = get_option( 'h2cweb_rpi_options' );

    $h2cweb_rpi_title = $options['h2cweb_rpi_title'];

    $rpi_count = $options['rpi_count'];

    $rpi_order = $options['rpi_order'];

    $rpi_orderby = $options['rpi_orderby'];

    extract(shortcode_atts(array(

        'count' => $rpi_count,

         ), $atts));

        global $post;

        $current_cat = get_the_category($post->ID);

        $current_cat = $current_cat[0]->cat_ID;

        $this_cat = '';

        $tag_ids = array();

        $tags = get_the_tags($post->ID);

        if ($tags) {

            foreach($tags as $tag) {

                $tag_ids[] = $tag->term_id;

                }

        } else {

        $this_cat = $current_cat;

            }

        $args = array(

            'post_type' => get_post_type(),

            'numberposts' => $count,

            'orderby' => $rpi_orderby,

            'order' => $rpi_order,

            'tag__in' => $tag_ids,

            'cat' => $this_cat,

            'exclude' => $post->ID

            );

        $dtwd_related_posts = get_posts($args);

            if ( empty($dtwd_related_posts) ) {

                $args['tag__in'] = '';

                $args['cat'] = $current_cat;

                $dtwd_related_posts = get_posts($args);

                }

            if ( empty($dtwd_related_posts) ) {

                    return;
                }

    $post_list = '';

    foreach($dtwd_related_posts as $dtwd_related) {

        $options = get_option( 'h2cweb_rpi_options' );

        $h2cweb_rpi_title = $options['h2cweb_rpi_title'];

        $title=$h2cweb_rpi_title ;

        $post_list .= '<li><a href="' . get_permalink($dtwd_related->ID) . '">' . $dtwd_related->post_title . '</a></li>';

        }

        return sprintf('

            <div class="h2cweb_related-posts">

                <h4>%s</h4>

                <ul style="list-style:none; margin:0px;">%s</ul>

            </div> 

        ', $title, $post_list );

        }

add_shortcode('rpi', 'h2cweb_related_posts_shortcode');

?>