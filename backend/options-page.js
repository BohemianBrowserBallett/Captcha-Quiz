<script>
  jQuery(document).ready(function() {
    function addDelete() {
      jQuery('.js-quizcaptcha_delete').on('click', function(e){
        console.log('yep');
        e.preventDefault();
        if(jQuery('.js-quizcaptcha_container').length>1) {
          jQuery(this).closest('.js-quizcaptcha_container').remove();
        }
      });
    }
    addDelete();
    jQuery('#js-quizcaptcha_addquestion').on('click', function(e){
      e.preventDefault();
      var newQuestion = jQuery('.js-quizcaptcha_container').last().clone().appendTo('#js-quizcaptcha_questions');
      var count = parseInt(newQuestion.data('quizcaptcha-count'))+1;
      newQuestion.data('quizcaptcha-count',count);
      newQuestion.find('.js-quizcaptcha_question').attr('name','questions['+count+'][text]');
      newQuestion.find('.js-quizcaptcha_question').val('');
      newQuestion.find('.js-quizcaptcha_answer-a').attr('name','questions['+count+'][a]');
      newQuestion.find('.js-quizcaptcha_answer-a').val('');
      newQuestion.find('.js-quizcaptcha_answer-b').attr('name','questions['+count+'][b]');
      newQuestion.find('.js-quizcaptcha_answer-b').val('');
      newQuestion.find('.js-quizcaptcha_answer-c').attr('name','questions['+count+'][c]');
      newQuestion.find('.js-quizcaptcha_answer-c').val('');
      newQuestion.find('.js-quizcaptcha-correct').attr('name','questions['+count+'][answer]');
      newQuestion.find('.js-quizcaptcha-correct').val('');
      addDelete();
    });
  });
</script>
