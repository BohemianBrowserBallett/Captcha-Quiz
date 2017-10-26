<?php
/*
Plugin Name: Quiz Captcha
Description:
Version: 1.0
Author: Bohemian Browser Ballett
Author URI: http://turbokultur.com

Comments: Not translation ready yet
*/


require_once dirname( __FILE__ ) .'/backend/options-page.php';
use quiz_captcha\backend\options_page;
require_once dirname( __FILE__ ) .'/backend/change-data.php';
use quiz_captcha\backend\change_data;
require_once dirname( __FILE__ ) .'/frontend/display-captcha.php';
use quiz_captcha\frontend\display_captcha;
require_once dirname( __FILE__ ) .'/frontend/get-data.php';
use quiz_captcha\frontend\get_data;


//complete list of actions and filters

//https://codex.wordpress.org/Plugin_API/Action_Reference/admin_menu
add_action("admin_menu", function(){
  //https://developer.wordpress.org/reference/functions/add_menu_page/
	add_menu_page("Quiz Captcha", "Quiz Captcha", "manage_options", "quiz-captcha", "quiz_captcha\\backend\\options_page\\display_options_page", null, 99);
});

//https://codex.wordpress.org/Plugin_API/Action_Reference/admin_post_(action)
add_action( 'admin_post_save_quizcaptcha_options', 'quiz_captcha\\backend\\change_data\\update_options' );

//https://codex.wordpress.org/AJAX_in_Plugins
add_action( 'wp_ajax_new_quizcaptcha_questions', 'quiz_captcha\\frontend\\get_data\\ajax_return_questions' );
add_action( 'wp_ajax_nopriv_new_quizcaptcha_questions', 'quiz_captcha\\frontend\\get_data\\ajax_return_questions' );

//https://codex.wordpress.org/Plugin_API/Filter_Reference/comments_template
add_filter( "comments_template", function($unfiltered_value) {
	if(quizcaptcha_installation_complete()) {
		quiz_captcha\frontend\display_captcha\include_frontend();
	}
	return $unfiltered_value;
});

function quizcaptcha_installation_complete() {
	$option = get_option('quizcaptcha_options');
	if(
		isset($option)
		&& $option
		&& isset($option['quizcaptcha_fail_text'])
		&& $option['quizcaptcha_fail_text'] != ""
		&& isset($option['quizcaptcha_explanation_text'])
		&& $option['quizcaptcha_explanation_text'] != ""
		&& count($option['questions']) > 2
	){
		foreach($question['questions'] as $question){
			if(
				!isset($question['text'])
				|| $question['text'] == ""
				|| !isset($question['answer'])
				|| $question['answer'] == ""
				|| !isset($question['a'])
				|| $question['a'] == ""
				|| !isset($question['b'])
				|| $question['b'] == ""
				|| !isset($question['c'])
				|| $question['c'] == ""
			){
				return false;
			}
		}
		return true;
	}
	return false;
}


?>
