<?php
/*
Plugin Name: Reusable Links
Version: 0.1
Updated: 2009-06-18
Plugin URI: http://
Description: This plugin automatically builds a link using the data in the wp_links table. Use the format {{url:Link Name}}
If you put pipes (| |) around the text of one of your links (in the link manager), this plugin will link that text to its uri from the links table. When a link changes, just update it via the Links Manager and it will update throughout your site.
Requires at least: 3.5
Tested up to: 3.5
In WordPress 3.5, the Link Manager was disabled by default. This plugin enables the Links Manager.
http://core.trac.wordpress.org/ticket/21307
Author: Originally created by Saumedia, Inc. and was maintained from 2008 - 2011.
Author URI: N/A
Tags: links, link manager, external links
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
---------------------------------------------------------------------
*/

/*
Process Flow:
--------------------
1) Locate link
2) Exists in db?
3) Replace w/html
4) create an array $template_vars=array("FOO" => "The PHP Way", "BAR" => "PHPro.orG");
5) Regex and evaluate $string = preg_replace("/{{(.*?)}}/ime", "\$template_vars['$1']",$string);
6) return
*/

function rmk_content($matches) {
    global $wpdb;
    
    //Vars...
    $text 				= $matches[1];
    $mm					= substr($text,4);
	$mm_type 			= substr($text,0,4);

    if($mm_type === 'url:') {
	    $sql = "SELECT * FROM wp_links WHERE link_name='$mm'";
    }
    
    $mm_content 	= $wpdb->get_row($sql, ARRAY_A);
    $mm_content_ref = $wpdb->get_row($sql_ref, ARRAY_A);
    if (count($mm_content) === 0) { return 'The artifact requested was not found'; } 
        elseif ($mm_type === 'url:') {
	        $link_name 		= $mm_content['link_name'];
    	    $link_url 		= $mm_content['link_url'];
        	$link_desc 		= $mm_content['link_description'];
        	$link_target 	= $mm_content['link_target'];
	        $link_text 		= "<a href='$link_url' title='$link_desc' target='$link_target'>$link_name</a>";
	        $vid_player		= 'Link';
		}
		
		$players	= array('Link'=>$link_text);
	   	return $players[$vid_player];
}

function rmk_getcontent( $content ) {
	$regex_post_content	= "/{{(.*?)}}/im";  // Find tagged content, transclusion
	$rm_mm = preg_replace_callback( $regex_post_content, "rmk_content", $content );
	return $rm_mm;
}

add_filter( 'pre_option_link_manager_enabled', '__return_true' );
add_action('wp_head', 'wp_reusable_head');
add_filter( 'the_content', 'rmk_getcontent' );

?>