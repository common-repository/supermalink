<?php

/************************************************************************************
 * Plugin Name: Supermalink
 * Plugin URI: http://tutslog.com/tutorials/plugin-wordpress-supermalink-139.html
 * Description: Make your permalink friendly, SEO friendly URLs, clean URLs, etc... add dynamically created permalinks using the short code tag [supermalink]. <a href="options-general.php?page=supermalink_help">Need help?</a>
 * Author: Thanh D.
 * Author URI: http://tutslog.com
 * Version: 1.1
 ************************************************************************************/

setlocale(LC_ALL, 'en_US.UTF8');
add_action('admin_menu', 'supermalink_menu');
add_filter('sanitize_title', 'supermalink_slug');
add_filter('remove_accents', 'supermalink_slug');
add_shortcode('supermalink', 'supermalink_shortcode');
/**
 * General permalink using shortcode
 * [supermalink id=101]This is 101rd post[/supermalink]
 * [supermalink]This is current post[/supermalink]
 * [supermalink id=123 rel="post" class="your_class" target="_blank"]Example[/supermalink]
 */
function supermalink_shortcode($atts, $txt = null) {
	extract(shortcode_atts(array('id' => null, 'target' => null, 'class' => '', 'rel' => null, 'append' => ''), $atts));

	$txt 		= 	trim($txt);
	$id 		= 	empty($id) ? get_the_ID() : $id;
	$permalink 	= 	get_permalink($id);

	if (empty($txt)) {
		$return = $permalink . $append;
	} else {
		$return = 	'<a href="' . $permalink . esc_attr($append) . '"';
		$return.=	empty($target)?'':' target="' . $target . '"';
		$return.= 	$class?' class="supermalink-link ' . $class . '"':' class="supermalink-link"';
		$return.=	empty($rel)?'':' rel="' . $rel . '"';		
		$return.= 	'>' . str_replace('%post_title%', get_the_title($id), $txt) . '</a>';
	}
	return $return;
}

function supermalink_slug($title) {
    if (!is_admin()) return $title;
	$_replacement	  =	'-';
	$_transliteration = array(
		'/ä|æ|ǽ/' => 'ae',
		'/ö|œ/' => 'oe',
		'/ü/' => 'ue',
		'/Ä/' => 'Ae',
		'/Ü/' => 'Ue',
		'/Ö/' => 'Oe',
		'/À|Á|Â|Ã|Å|Ǻ|Ā|Ă|Ą|Ǎ|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ằ|Ắ|Ặ|Ẳ|Ẵ/' => 'A',
		'/à|á|â|ã|å|ǻ|ā|ă|ą|ǎ|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ằ|ắ|ặ|ẳ|ẵ|å|ª/' => 'a',
		'/Ç|Ć|Ĉ|Ċ|Č/' => 'C',
		'/ç|ć|ĉ|ċ|č/' => 'c',
		'/Ð|Ď|Đ/' => 'D',
		'/ð|ď|đ/' => 'd',
		'/È|É|Ê|Ë|Ē|Ĕ|Ė|Ę|Ě|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ/' => 'E',
		'/è|é|ê|ë|ē|ĕ|ė|ę|ě|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ/' => 'e',
		'/Ĝ|Ğ|Ġ|Ģ/' => 'G',
		'/ĝ|ğ|ġ|ģ/' => 'g',
		'/Ĥ|Ħ/' => 'H',
		'/ĥ|ħ/' => 'h',
		'/Ì|Í|Î|Ï|Ĩ|Ī|Ĭ|Ǐ|Į|İ|Ị|Ỉ|Ĩ/' => 'I',
		'/ì|í|î|ï|ĩ|ī|ĭ|ǐ|į|ị|ỉ|ĩ|î|ı/' => 'i',
		'/Ĵ/' => 'J',
		'/ĵ/' => 'j',
		'/Ķ/' => 'K',
		'/ķ/' => 'k',
		'/Ĺ|Ļ|Ľ|Ŀ|Ł/' => 'L',
		'/ĺ|ļ|ľ|ŀ|ł/' => 'l',
		'/Ñ|Ń|Ņ|Ň/' => 'N',
		'/ñ|ń|ņ|ň|ŉ/' => 'n',
		'/Ò|Ó|Ô|Õ|Ō|Ŏ|Ǒ|Ő|Ơ|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ|Ø|Ǿ/' => 'O',
		'/ò|ó|ô|õ|ō|ŏ|ǒ|ő|ơ|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ|ø|ǿ|º/' => 'o',
		'/Ŕ|Ŗ|Ř/' => 'R',
		'/ŕ|ŗ|ř/' => 'r',
		'/Ś|Ŝ|Ş|Ș|Š/' => 'S',
		'/ś|ŝ|ş|ș|š|ſ/' => 's',
		'/Ţ|Ț|Ť|Ŧ/' => 'T',
		'/ţ|ț|ť|ŧ/' => 't',
		'/Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ|Û|Ũ|Ū|Ŭ|Ů|Ű|Ų|Ư|Ǔ|Ǖ|Ǘ|Ǚ|Ǜ/' => 'U',
		'/ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ|û|ũ|ū|ŭ|ů|ű|ų|ư|ǔ|ǖ|ǘ|ǚ|ǜ/' => 'u',
		'/Ỳ|Ý|Ỵ|Ỷ|Ỹ|Ÿ|Ŷ/' => 'Y',
		'/ỳ|ý|ỵ|ỷ|ỹ|ÿ|ŷ/' => 'y',
		'/Ŵ/' => 'W',
		'/ŵ/' => 'w',
		'/Ź|Ż|Ž/' => 'Z',
		'/ź|ż|ž/' => 'z',
		'/Æ|Ǽ/' => 'AE',
		'/ß/' => 'ss',
		'/Ĳ/' => 'IJ',
		'/ĳ/' => 'ij',
		'/Œ/' => 'OE',
		'/ƒ/' => 'f'
	);
	
	$_quotedReplacement = preg_quote($_replacement, '/');
	$_merge= array(
		'/[^\s\p{Ll}\p{Lm}\p{Lo}\p{Lt}\p{Lu}\p{Nd}]/mu' => ' ',
		'/\\s+/' => $_replacement,sprintf('/^[%s]+|[%s]+$/', 
		$_quotedReplacement, $_quotedReplacement) => ''
	);

	$_map  = $_transliteration + $_merge;
	$title = preg_replace(array_keys($_map), array_values($_map), urldecode($title));
	$clean = iconv('UTF-8', 'ASCII//TRANSLIT', $title);
	$clean = preg_replace("/[^a-zA-Z0-9\/_| -]/", '', $clean);
	$clean = strtolower(trim($clean, '-'));
	return preg_replace("/[\/_| -]+/", '-', $clean);
}

function supermalink_menu(){
	add_submenu_page('options-general.php', 'Supermalink help', 'Supermalink help', 1, 'supermalink_help', 'supermalink_help');
}

function supermalink_reset(){
	global $wpdb;
	$posts 	= 	$wpdb->get_results("SELECT * FROM " . $wpdb->posts . " ORDER BY id ASC");
	foreach ($posts as $post):
		$post->post_title = supermalink_slug($post->post_title);
		$sql	=	"UPDATE $wpdb->posts SET `guid` = '$post->post_title', `post_name` = '$post->post_title' WHERE id = '$post->ID';";
		$update = 	$wpdb->query($sql);
	endforeach;
	echo '<div class="updated"><p>All permalinks were changed! (Effected post: '.count($posts).')</p></div>"';
}

function supermalink_help() {
?>
<div class="wrap" style="width:80%;">
	<h2>Supermalink help</h2>
	<p><strong>The Basics</strong></p>
    <p>Make a SEO friendly URLs, clean URLs, permalinks, etc. You know, those nice-looking URLs that are easy to read (for humans and search engines).</p>
    <p>The keywords that show up in your URLs plan an important role in how well your site ranks in search results:</p>
    <pre><strong>Vietnamese:</strong><code>Sử Dụng Permalinks Cho Tiếng Việt</code>=><code>su-dung-permalinks-cho-tieng-viet</code></pre>
    <pre><strong>French:</strong><code>Qu'en est-il français? Ça marche alors?</code>=><code>quen-est-il-francais-ca-marche-alors</code></pre>
    <pre><strong>Italian:</strong><code>Perché l'erba è verde?</code>=><code>perche-lerba-e-verde</code></pre>
    <pre><strong>Swedish:</strong><code>Tänk efter nu – förr'n vi föser dig bort</code>=><code>tank-efter-nu-forrn-vi-foser-dig-bort</code></pre>
    
    <p>Inserting a dynamic permalink is easy using the <code>[supermalink]</code> short code:</p>
	<pre><code>[supermalink]This is a link of current post[/supermalink]</code></pre>
	<p>If you'd like create a permalink to a different post than the one being displayed, use the ID attribute:</p>
	<pre><code>[supermalink id=150]This is a link to post 150[/supermalink]</code></pre>
	
    <p>Inserting a permalink with anchor element attributes:</p>
	<p>Some users may want to add a CSS <code>class</code>, relationship (<code>rel</code>), 
	or even a <code>target</code> attribute to the links that supermalink outputs.</p>
	<p>The latter 3 attributes are supported in your <code>[supermalink]</code> short code:</p>
	<pre><code>[supermalink id=123 rel="post" class="your_class" target="_blank"]Open post 123 in a new window[/supermalink]</code></pre>
	
	<p><strong>Other Supermalink notes</strong></p>
	<p>If you need more support, email Thanh D<code><a href="mailto:tutslog.com@gmail.com">tutslog.com@gmail.com</a></code>
        or visit his blog at <code><a href="http://tutslog.com" target="_blank">tutslog.com</a></code>. <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=XY3NHXUX7XUD4" target="_blank">Donate me</a><code>to keep development active!</code></p>
<?php }?>