<?php

namespace quiz_captcha\backend\options_page;

function display_options_page(){

	//http://php.net/manual/de/function.file-get-contents.php
	$cssFile = file_get_contents(dirname( __FILE__ ) .'/options-page.css');
	$jsFile = file_get_contents(dirname( __FILE__ ) .'/options-page.js');
	//js file gets printed after html code

	//https://codex.wordpress.org/Function_Reference/get_transient
	$transient_data = get_transient( 'quizcaptcha_options_page_input' );
	//https://developer.wordpress.org/reference/functions/get_option/
	$option_data = get_option('quizcaptcha_options');

	//check for previous saved data
	$form_data = [];
	$no_saved_data = false;

	//check if data is saved in a transient -> options are currently being edited
	if($transient_data) {
		$form_data = $transient_data;
	}
	//if not, load the values from the options value
	else if($option_data) {
		$form_data = $option_data;
	}
	//if there is no data saved an empty form will be displayed
	else {
		$no_saved_data = true;
	}

	//https://codex.wordpress.org/Function_Reference/admin_url
	$adminUrl = admin_url('admin-post.php');
	//https://codex.wordpress.org/Function_Reference/wp_nonce_field
	$nonce = wp_nonce_field( 'add_quizcaptcha_options' );

	print_r($cssFile);
	//js file gets printed after html code
	?>
		<?php if(!$no_saved_data && isset($form_data['error_message'])) { ?>
			<div id="message" class="notice notice-error is-dismissible">
				<p> <?= $form_data['error_message'] ?>
				</p>
				<button type="button" class="notice-dismiss">
					<span class="screen-reader-text">Dismiss this notice.</span>
				</button>
			</div>
		<?php } ?>
		<div class="wrap">
			<h1 class="wp-heading-inline">Quiz Captcha</h1>
			<form action="<?= $adminUrl ?>" method="post">
				<div id="poststuff">
					<div id="post-body" class="metabox-holder columns-2">
						<div id="post-body-content" style="position: relative;">
							<div class="meta-box-sortables ui-sortable" style="">
								<div class="postbox">
									<h2 class=""><span>Options</span></h2>
									<div class="inside">
										<p style="font-weight:500;">Explanation Text</p>
										<textarea name="quizcaptcha_explanation_text" rows="10" cols="50" id="quizcaptcha_explanation" class="large-text code"><?php if(!$no_saved_data) { echo stripslashes($form_data['quizcaptcha_explanation_text']); } ?></textarea>
										<p style="font-weight:500;">Failure Text</p>
										<textarea name="quizcaptcha_fail_text" rows="10" id="quizcaptcha_explanation" class="large-text code"><?php if(!$no_saved_data) { echo stripslashes($form_data['quizcaptcha_fail_text']); } ?></textarea>
									</div>
								</div>
								<div class="postbox submitbox">
									<h2 class=" "><span>Questions</span></h2>
									<div class="inside" id="js-quizcaptcha_questions">

										<?php
											if(!$no_saved_data) {
												foreach($form_data['questions'] as $key => $question) {
													?>
														<div class="quizcaptcha_container js-quizcaptcha_container" data-quizcaptcha-count="<?= $key ?>">
															<div class="quizcaptcha_row quizcaptcha_row--grow">
																<textarea name="questions[<?= $key ?>][text]" rows="7" cols="50" class="large-text code quizcaptcha_question js-quizcaptcha_question"><?= stripslashes($question['text']) ?></textarea>
															</div>
															<div class="quizcaptcha_row">
																<input name="questions[<?= $key ?>][a]" type="text" size="30" placeholder="A" autocomplete="off" style="" class="quizcaptcha_answer js-quizcaptcha_answer-a" value="<?= stripslashes($question['a']) ?>">
																<input name="questions[<?= $key ?>][b]" type="text" size="30" placeholder="B" autocomplete="off" style="" class="quizcaptcha_answer js-quizcaptcha_answer-b" value="<?= stripslashes($question['b']) ?>">
																<input name="questions[<?= $key ?>][c]" type="text" size="30" placeholder="C" autocomplete="off" style="" class="quizcaptcha_answer js-quizcaptcha_answer-c" value="<?= stripslashes($question['c']) ?>">
																<select name="questions[<?= $key ?>][answer]" class="quizcaptcha_dropdown js-quizcaptcha-correct">
																	<option value="" disabled selected>Correct Answer</option>
																	<option value="0" <?php if($question['answer'] == '0'){ echo "selected"; } ?>>A is correct</option>
																	<option value="1" <?php if($question['answer'] == '1'){ echo "selected"; } ?>>B is correct</option>
																	<option value="2" <?php if($question['answer'] == '2'){ echo "selected"; } ?>>C is correct</option>
																</select>
															</div>
															<div class="quizcaptcha_row quizcaptcha_row--full">
																<a href="#" class="submitdelete deletion quizcaptcha_delete js-quizcaptcha_delete">Delete</a>
															</div>
														</div>
													<?php
												}
											}
											else {
												for($i=0;$i<3;$i++) {
													?>
														<div class="quizcaptcha_container js-quizcaptcha_container" data-quizcaptcha-count="<?= $i ?>">
															<div class="quizcaptcha_row quizcaptcha_row--grow">
																<textarea name="questions[<?= $i ?>][text]" rows="7" cols="50" class="large-text code quizcaptcha_question js-quizcaptcha_question"><?= stripslashes($question['text']) ?></textarea>
															</div>
															<div class="quizcaptcha_row">
																<input name="questions[<?= $i ?>][a]" type="text" size="30" placeholder="A" autocomplete="off" style="" class="quizcaptcha_answer js-quizcaptcha_answer-a" value="<?= stripslashes($question['a']) ?>">
																<input name="questions[<?= $i ?>][b]" type="text" size="30" placeholder="B" autocomplete="off" style="" class="quizcaptcha_answer js-quizcaptcha_answer-b" value="<?= stripslashes($question['b']) ?>">
																<input name="questions[<?= $i ?>][c]" type="text" size="30" placeholder="C" autocomplete="off" style="" class="quizcaptcha_answer js-quizcaptcha_answer-c" value="<?= stripslashes($question['c']) ?>">
																<select name="questions[<?= $i ?>][answer]" class="quizcaptcha_dropdown js-quizcaptcha-correct">
																	<option value="" disabled selected>Correct Answer</option>
																	<option value="0" >A is correct</option>
																	<option value="1" >B is correct</option>
																	<option value="2" >C is correct</option>
																</select>
															</div>
															<div class="quizcaptcha_row quizcaptcha_row--full">
																<a href="#" class="submitdelete deletion quizcaptcha_delete js-quizcaptcha_delete">Delete</a>
															</div>
														</div>
													<?php
												}
											}
										?>

									</div>
									<div class="quizcaptcha_add">
										<a id="js-quizcaptcha_addquestion" class="button button-large" style="margin-right: 10px; margin-top: 30px;">Add Question</a>
									</div>
								</div>
							</div>
						</div>
						<div id="postbox-container-1" class="postbox-container">
							<div class="postbox">
								<h2 class=""><span>Save Changes</span></h2>
								<div class="inside">
									<div class="submitbox" id="submitpost">
											<input type="hidden" name="action" value="save_quizcaptcha_options">
											<?= $nonce ?>
											<input class="button button-primary button-large" style="width:100%" type="submit" value="Save">
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	<?php
	print_r($jsFile);
}
