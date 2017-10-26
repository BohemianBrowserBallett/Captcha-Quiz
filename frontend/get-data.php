<?php

namespace quiz_captcha\frontend\get_data;

function load_text() {
  $data[] = stripslashes(get_option('quizcaptcha_options')['quizcaptcha_explanation_text']);
  $data[] = stripslashes(get_option('quizcaptcha_options')['quizcaptcha_fail_text']);
  return $data;
}

function load_questions() {
  //https://developer.wordpress.org/reference/functions/get_option/
  $questions = get_option('quizcaptcha_options')['questions'];
  shuffle($questions);

  $localizedQuizcaptchaData = [];

  for($i = 0; $i <3; $i++) {
    $question = array(
      'question' => stripslashes($questions[$i]['text']),
      'answers' => array(
          'answer0' => stripslashes($questions[$i]['a']),
          'answer1' => stripslashes($questions[$i]['b']),
          'answer2' => stripslashes($questions[$i]['c'])
      ),
      'correct_answer' => $questions[$i]['answer']
    );
    $correct_answer = $question['answers']['answer'.$question['correct_answer']];
    shuffle($question['answers']);
    for($o = 0; $o<=2; $o++) {
      if($correct_answer == $question['answers'][$o]){
        $question['correct_answer'] = $o;
      }
    }
    $localizedQuizcaptchaData['question'.$i] = $question;
  }

  return $localizedQuizcaptchaData;
}

function ajax_return_questions() {
  //https://codex.wordpress.org/Function_Reference/wp_send_json
  wp_send_json(load_questions());
  die();

}


 ?>
