var quizCaptchaData = localizedQuizcaptchaData;

var quizCaptcha = (function() {

  var currentQuestion = 0;

  function addEventListeners() {
    jQuery('#commentform #comment').on('focus', openQuiz);
    jQuery('#js-quizcaptcha').on('click', function(e){
      jQuery('#js-quizcaptcha').css('display','none');
    });
    jQuery('#js-quizcaptcha-failurebutton').on('click', function(e){
      jQuery('#js-quizcaptcha').css('display','none');
      reset();
    });
    jQuery('#js-quizcaptcha-card').on('click', function(e){
      e.stopPropagation();
    });
  }

  function openQuiz(e) {
    if(!checkCookie()) {
      e.preventDefault();
      jQuery('#js-quizcaptcha').css('display','flex');
    }
  }

  function nextQuestion() {
    if(currentQuestion != 0) {
      jQuery('#js-quizcaptcha-explanation').css('display','none');
    }
    jQuery('#js-quizcaptcha-question-label').html("Frage" + (currentQuestion+1) + "/3");
    jQuery('#js-quizcaptcha-question-text').html(quizCaptchaData['question'+currentQuestion].question);
    quizCaptchaData['question'+currentQuestion].answers.forEach(function(item, index){
      jQuery('#js-quizcaptcha-answer-'+index).off('click', correctAnswer);
      jQuery('#js-quizcaptcha-answer-'+index).off('click', wrongAnswer);
      jQuery('#js-quizcaptcha-answer-'+index).on('click', wrongAnswer);
      jQuery('#js-quizcaptcha-answer-'+index).find('.js-quizcaptcha-answer-text').html(item);
    });
    jQuery('#js-quizcaptcha-answer-'+quizCaptchaData['question'+currentQuestion]['correct_answer']).on('click',correctAnswer);
    jQuery('#js-quizcaptcha-answer-'+quizCaptchaData['question'+currentQuestion]['correct_answer']).off('click',wrongAnswer);
  }

  function correctAnswer() {
    if(currentQuestion == 2) {
      jQuery('#commentform').off('submit', openQuiz);
      submitForm();
    }
    else {
      currentQuestion++;
      nextQuestion();
    }
  }

  function wrongAnswer() {
    jQuery('#js-quizcaptcha-question').css('display','none');
    jQuery('#js-quizcaptcha-explanation').css('display','none');
    jQuery('#js-quizcaptcha-failure').css('display','block');
  }

  function reset() {
    jQuery('#js-quizcaptcha-question').css('display','block');
    jQuery('#js-quizcaptcha-explanation').css('display','block');
    jQuery('#js-quizcaptcha-failure').css('display','none');
    loadNewQuestions();
    currentQuestion = 0;
    nextQuestion();
  }

  function loadNewQuestions() {
    var data = {
			'action': 'new_quizcaptcha_questions'
		};
    jQuery.post(quizcaptcha_ajax_url, data, function(response) {
			quizCaptchaData = response;
		});
  }

  function submitForm() {
    //var submitFormFunction = Object.getPrototypeOf(document.getElementById('commentform')).submit;
    setCookie();
    jQuery('#js-quizcaptcha').css('display','none');
    reset();
    //submitFormFunction.call(document.getElementById('commentform'));
  }

  function setCookie() {
    var date = new Date();
    //30 days
    date.setTime(date.getTime() + (3 * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toGMTString();
    document.cookie = "quizcaptcha=true" + expires + "; path=/";
  }

  function checkCookie() {
    //return false;
    if (document.cookie.length > 0) {
      c_start = document.cookie.indexOf("quizcaptcha=");
      if (c_start != -1) {
          c_start = c_start +  "quizcaptcha".length + 1;
          c_end = document.cookie.indexOf(";", c_start);
          if (c_end == -1) {
              c_end = document.cookie.length;
          }
          return true;
      }
      else {
        return false;
      }
    }
    return false;
  }

  return {

      init: function() {

        addEventListeners();
        nextQuestion();
        console.log('Quiz Captcha initialized');

      }

  };

}());


quizCaptcha.init();
