<?php

namespace quiz_captcha\backend\change_data;

function update_options() {

	//https://developer.wordpress.org/reference/functions/is_user_logged_in/
	//https://codex.wordpress.org/Function_Reference/current_user_can
	if(!is_user_logged_in() || !current_user_can('manage_options') ) {
		die();
	}

	//https://codex.wordpress.org/Function_Reference/check_admin_referer
	$check_nonce = check_admin_referer( 'add_quizcaptcha_options');

	function response($error_message) {
		$transient_data = $_POST;
		$transient_data['error_message'] = $error_message;
		unset($transient_data['_wpnonce']);
		unset($transient_data['_wp_http_referer']);
		unset($transient_data['action']);
		//https://codex.wordpress.org/Function_Reference/set_transient
		set_transient( 'quizcaptcha_options_page_input', $transient_data , 60*60*0.1 );
		//https://developer.wordpress.org/reference/functions/wp_redirect/
		wp_redirect(admin_url('admin.php?page=quiz-captcha'));
		die();
	}

	//input includes explanation text
	if(!isset($_POST['quizcaptcha_explanation_text']) || $_POST['quizcaptcha_explanation_text'] == "") {
		response('Please insert an explanation text.');
	}

	//input includes fail text
	if(!isset($_POST['quizcaptcha_fail_text']) || $_POST['quizcaptcha_fail_text'] == "") {
		response('Please insert a message to display after wrong answers.');
	}

	//input includes questions and has at least 3
	if(!isset($_POST['questions']) || sizeof($_POST['questions']) < 3 ) {
		response('Please define at least 3 questions for the Quiz-Captcha.');
	}

	foreach($_POST['questions'] as $question) {
		if(
			!isset($question['text']) || $question['text'] == ""
			|| !isset($question['a']) || $question['a'] == ""
			|| !isset($question['b']) || $question['b'] == ""
			|| !isset($question['c']) || $question['c'] == ""
			|| !isset($question['answer']) || $question['answer'] == ""
		) {
			response('Please fill out all necessary fields for each of your questions');
		}
	}

	// ALL VALID
	//as response will redirect and die, this point is only reached on succesful validation

	//used for testing
	// echo "<pre>";
	// print_r($_POST);
	// die();

	$sanitized_data = $_POST;
	unset($sanitized_data['action']);
	unset($sanitized_data['_wpnonce']);
	unset($sanitized_data['_wp_http_referer']);
	//https://codex.wordpress.org/Function_Reference/update_option
	update_option('quizcaptcha_options',$sanitized_data,0);
	delete_transient('quizcaptcha_options_page_input');
	wp_redirect(admin_url('admin.php?page=quiz-captcha'));
	die();

}



 ?>
