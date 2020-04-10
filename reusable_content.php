<?php
/*
Plugin Name: Reusable Links
Version: 0.1
Updated: 2009-06-18
Plugin URI: http://
Description: A WordPress plugin that automatically builds a link from data in the wp_links table. Don't search and replace links, just update a link in the Links Manager and those links created with this plugin are automatically updated. This application looks for text enclosed within two curly braces. Let the system know that you want to replace this text with a link by using the 'url' identifier followed by the name of the link. Use the format {{url:Link Name}}. See README for more information.
Author: Saumedia, Inc. (2008); handed off in 2020
Author URI: N/A
Tags: links, link manager, external links
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
---------------------------------------------------------------------

Copyright [2020] [Robert Saum]

Licensed under the GNU GENERAL PUBLIC LICENSE, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.gnu.org/licenses/gpl-2.0.html

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

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
	        $link_text 		= "<a href='$link_url' title='$link_desc' target='$link_target' rel='noreferrer noopener'>$link_name</a>";
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