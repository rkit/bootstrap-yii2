var $ = require('jquery');

function showAlert($form) {
  var $alert = $(
    '<div class="form-alert callout callout-danger animated fadeInUp" />'
  ).hide().html(
    '<h4>Критическая ошибка</h4>' +
    '<p>Извините, возникли проблемы, попробуйте позже…</p>'
  );

  $form.find('.form-alert').remove();
  $form.data('yiiActiveForm')
    .submitObject
    .closest('.form-controls')
    .prepend($alert);

  $alert.fadeIn(100);
}

$('.ajax-form').yiiAjaxForm({
  beforeSend: function() {
    var $button = $(this).data('yiiActiveForm').submitObject;
    if ($button) {
      $button.button('loading');
    }
  },

  error: function(jqXHR) {
    if (jqXHR.status && jqXHR.status === 302) {
      return true;
    }

    showAlert($(this));
  },

  complete: function() {
    var $button = $(this).data('yiiActiveForm').submitObject;
    if ($button) {
      $button.button('reset');
    }
  },

  success: function(data) {
    if (data === false) {
      showAlert($(this));
      return;
    }

    if (data.redirect) {
      document.location.href = data.redirect;
    } else if (data.reload) {
      location.reload(true);
    }
    // show validation error messages
    $(this).yiiActiveForm('updateMessages', data);
  },
});
