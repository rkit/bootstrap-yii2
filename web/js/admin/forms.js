function showAlert($form, jqXHR) {
  if (jqXHR.responseJSON) {
    renderAlert($form, jqXHR.responseJSON.name, jqXHR.responseJSON.message);
  } else if (jqXHR.responseText) {
    renderAlert($form, jqXHR.statusText, jqXHR.responseText);
  }
}

function renderAlert($form, header, text) {
  var alert = $(
    '<div class="form-alert callout callout-danger animated fadeInUp" />'
  ).html('<h4>' + header + '</h4>' + '<p>' + text + '</p>');

  var submitObject = $form.data('yiiActiveForm').submitObject;
  if (submitObject) {
    submitObject.closest('.form-controls').prepend(alert);
  }
}

function hideAlert($form) {
  $form.find('.form-alert').remove();
}

(function() {
  $('.ajax-form').yiiAjaxForm({
    beforeSend: function() {
      hideAlert($(this));

      var submitObject = $(this).data('yiiActiveForm').submitObject;
      if (submitObject) {
        submitObject.button('loading');
      }
    },

    error: function(jqXHR) {
      var status = jqXHR.status;
      if (status === 301 || status === 302) {
        return;
      }

      switch (status) {
        case 422:
          $(this).yiiActiveForm('updateMessages', jqXHR.responseJSON);
          break;
        default:
          showAlert($(this), jqXHR);
      }
    },

    complete: function() {
      var submitObject = $(this).data('yiiActiveForm').submitObject;
      if (submitObject) {
        submitObject.button('reset');
      }
    },

    success: function() {},
  });
})();
