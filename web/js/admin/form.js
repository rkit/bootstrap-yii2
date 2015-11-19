var $ = require('jquery');
require('jquery-form');

/**
 * AJAX Form based on jquery-form
 * Usage: <form class="form">…</form>
 */
var form = {
  ALERT_WARNING: 'warning',
  ALERT_DANGER: 'danger',
  ALERT_INFO: 'info',

  init: function() {
    $('.form').on('click', ':submit', function() {
      $(this).addClass('submitted');
    });

    $('.form').ajaxForm({
      delegation: true,
      beforeSubmit: form.beforeSubmit,
      complete: form.complete,
      error: form.error,
      success: form.success,
    });
  },

  beforeSubmit: function(formData, $form) {
    $form
      .find(':submit').prop('disabled', true).end()
      .find('.submitted').button('loading').end()
      .find('.alert').remove();

    form.clearErrors($form);

    return true;
  },

  complete: function(data, statusText, $form) {
    $form.find('.submitted').button('reset');
    $form.find(':submit').prop('disabled', false).removeClass('submitted');
  },

  error: function(data, statusText, xhr, $form) {
    if (data.status !== 302) {
      form.alert(
        $form,
        'Извините, попробуйте позже',
        form.ALERT_DANGER,
        'Критическая ошибка'
      );
    }
  },

  success: function(data, statusText, xhr, $form) {
    if (data.redirect) {
      document.location.href = data.redirect;
    }

    if (data.reload) {
      location.reload(true);
    }

    if (data.errors) {
      form.showErrors($form, data.errors, data.prefix);
    }
  },

  showErrors: function($form, errors, prefix) {
    var c = 0;
    $.each(errors, function(fieldId, text) {
      form.showError($form, prefix + fieldId, text); c++;
    });

    form.alert(
      $form,
      'Пожалуйста, исправьте ошибки',
      form.ALERT_WARNING,
      'Найдено ошибок: <span class="forms-error-count">' + c + '</span>'
    );
  },

  showError: function($form, fieldId, text) {
    var $field = $form.find('#' + fieldId.toLowerCase()).closest('.form-group');
    $field.find('.help-block').text(text);
    $field.addClass('has-error');
  },

  clearErrors: function(el) {
    $(el).find('.has-error .help-block').empty();
    $(el).find('.has-error').removeClass('has-error');
  },

  clearError: function(fieldId) {
    var $field = $('#' + fieldId.toLowerCase()).closest('.form-group');
    $field.find('.help-block').empty();
    $field.removeClass('has-error');
  },

  alert: function($form, text, type, description) {
    $form.find('.forms-callout').remove();

    $('<div style="display:none" class="forms-callout" />')
      .addClass('callout animated fadeInUp callout-' + (type ? type : form.ALERT_WARNING))
      .html('<h4>' + text + '</h4><p>' + description + '</p>')
      .insertBefore($form.find('.form-controls'))
      .fadeIn(100);
  },
};

module.exports = form;
