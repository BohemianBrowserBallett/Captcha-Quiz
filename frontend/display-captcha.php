<?php

namespace quiz_captcha\frontend\display_captcha;

require_once dirname( __FILE__ ) .'/get-data.php';
use quiz_captcha\frontend\get_data;

function include_frontend() {
  wp_register_script( 'quiz-captcha-script', plugins_url( 'display-captcha.js', __FILE__ ), array(), '1');
  wp_register_style( 'quiz-captcha-style', plugins_url( 'display-captcha.css', __FILE__ ), false, '1');

  $text = get_data\load_text();
  $localizedQuizcaptchaData = get_data\load_questions();
  $quizcaptcha_ajax_url = admin_url( 'admin-ajax.php' );

  //include js and css
  wp_enqueue_script( 'quiz-captcha-script');
  wp_enqueue_style ( 'quiz-captcha-style' );

  wp_localize_script( 'quiz-captcha-script', 'localizedQuizcaptchaData', $localizedQuizcaptchaData );
  wp_localize_script( 'quiz-captcha-script', 'quizcaptcha_ajax_url', $quizcaptcha_ajax_url );

  ?>

  <div class="quizcaptcha-background" id="js-quizcaptcha">
    <div class="quizcaptcha-card" id="js-quizcaptcha-card">
      <p class="quizcaptcha-explanation"  id="js-quizcaptcha-explanation">
        <?= $text[0] ?>
      </p>
      <div class="quizcaptcha-question" id="js-quizcaptcha-question">
        <p class="quizcaptcha-question__label" id="js-quizcaptcha-question-label">Frage 1/3</p>
        <p class="quizcaptcha-question__text" id="js-quizcaptcha-question-text"></p>
        <div class="quizcaptcha-answers" id="js-quizcaptcha-answers">
          <div class="quizcaptcha-answer" id="js-quizcaptcha-answer-0">
            <p class="quizcaptcha-answer__numeration">A</p>
            <p class="quizcaptcha-answer__text js-quizcaptcha-answer-text"></p>
          </div>
          <div class="quizcaptcha-answer" id="js-quizcaptcha-answer-1">
            <p class="quizcaptcha-answer__numeration">B</p>
            <p class="quizcaptcha-answer__text js-quizcaptcha-answer-text"></p>
          </div>
          <div class="quizcaptcha-answer" id="js-quizcaptcha-answer-2">
            <p class="quizcaptcha-answer__numeration">C</p>
            <p class="quizcaptcha-answer__text js-quizcaptcha-answer-text"></p>
          </div>
        </div>
      </div>
      <div class="quizcaptcha-failure" id="js-quizcaptcha-failure">
        <p class="quizcaptcha-failure__text">
          <?= $text[1] ?>
        </p>
        <div class="quizcaptcha-failure__button" id="js-quizcaptcha-failurebutton">zurück</div>
      </div>
    </div>
  </div>

<?php } ?>
