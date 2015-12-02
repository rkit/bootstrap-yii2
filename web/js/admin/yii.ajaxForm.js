/**
 * Yii Ajax Form
 *
 * This is the plugin used by the yii\widgets\ActiveForm widget.
 *
 * @copyright Copyright (c) 2015 Igor Romanov
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 * @author Igor Romanov <rkit.ru@gmail.com>
 */
(function($) {
  $.fn.yiiAjaxForm = function() {
    function getFormData($form, yiiActiveFormData) {
      var $button = yiiActiveFormData.submitObject;
      var formData = '&' + yiiActiveFormData.settings.ajaxParam + '=' + $form.attr('id');
      if ($button && $button.length && $button.attr('name')) {
        formData += '&' + $button.attr('name') + '=' + $button.attr('value');
      }

      return $form.serialize() + formData;
    }

    function init() {
      var $form = $(this);
      var yiiActiveFormData = $form.data('yiiActiveForm');
      var $button = yiiActiveFormData.submitObject;

      $.ajax({
        url: $form.attr('action'),
        type: $form.attr('method'),
        data: getFormData($form, yiiActiveFormData),
        dataType: yiiActiveFormData.settings.ajaxDataType,
        beforeSend: function(jqXHR, settings) {
          $form.trigger('ajaxFormBeforeSend', [$form, $button, jqXHR, settings]);
        },
        error: function(jqXHR, textStatus, errorThrown) {
          $form.trigger('ajaxFormError', [$form, $button, jqXHR, textStatus, errorThrown]);
        },
        complete: function(jqXHR, textStatus) {
          $form.trigger('ajaxFormComplete', [$form, $button, jqXHR, textStatus]);
        },
        success: function(data, textStatus, jqXHR) {
          $form.trigger('ajaxFormSuccess', [$form, $button, data, textStatus, jqXHR]);
          $form.yiiActiveForm('updateMessages', data);
        },
      });

      return false;
    }

    this.on('beforeSubmit', init);

    return this;
  };
})(window.jQuery);
