var $ = require('jquery');

$(function() {
  /**
   * AJAX Form
   */
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

      var $alert = $(
        '<div class="form-alert callout callout-danger animated fadeInUp" />'
      ).hide().html(
        '<h4>Критическая ошибка</h4>' +
        '<p>Извините, возникли проблемы, попробуйте позже…</p>'
      );

      $(this).find('.form-alert').remove();
      $(this).data('yiiActiveForm')
        .submitObject
        .closest('.form-controls')
        .prepend($alert);

      $alert.fadeIn(100);
    },
    complete: function() {
      var $button = $(this).data('yiiActiveForm').submitObject;
      if ($button) {
        $button.button('reset');
      }
    },
    success: function(data) {
      if (data.redirect) {
        document.location.href = data.redirect;
      } else if (data.reload) {
        location.reload(true);
      }
      // show validation error messages
      $(this).yiiActiveForm('updateMessages', data);
    },
  });

  /**
   * Menu toggle
   */
  $('.sidebar').on('click', 'a', function() {
    if ($(this).next('ul').length) {
      $('.sidebar li .nav').hide();
      $(this).next('.nav').toggle();
    }
  });

  /**
   * Confirmation
   * Usage:
   * <div class="confirmation" data-confirmation="Are you ok?">…</div>
   */
  $(document).on('click', '.confirmation', function(e) {
    if (!confirm($(this).data('confirmation'))) {
      e.stopImmediatePropagation();
      return false;
    }
  });

  /**
   * Ajax filter in GridView
   * Usage:
   * GridView::widget(['options' => ['class' => 'gridview']])
   */
  $(document).on('click', '.gridview .submit', function() {
    var _this = this;
    var $form = $(this).closest('form');

    $.post(
      $(_this).prop('href') ? $(_this).prop('href') : $form.prop('action'),
      $form.serialize() + '&' + $(_this).prop('name') + '=' + $(_this).val(),
      function() {
        $(_this).closest('.gridview').yiiGridView('applyFilter');
        return false;
      }

    );

    return false;
  });

  /**
   * Highlight a line in GridView
   * Usage:
   * GridView::widget(['options' => ['class' => 'gridview']])
   */
  $(document).on('click', '.gridview tbody tr td input[type="checkbox"]', function() {
    if ($(this).is(':checked')) {
      $(this).closest('tr').addClass('active');
    } else {
      $(this).closest('tr').removeClass('active');
    }

    var $gridview = $(this).closest('.gridview');
    var $operations = $gridview.find('.operations button');

    if ($gridview.find('tbody input:checked').size()) {
      $operations.removeClass('disabled');
    } else {
      $operations.addClass('disabled');
    }
  });

  /**
   * Select all checkboxes in GridView
   * Usage:
   * GridView::widget(['options' => ['class' => 'gridview']])
   */
  $(document).on('click', '.gridview .select-on-check-all', function() {
    var $checks = $(this).closest('table').find('tbody input[type="checkbox"]').closest('tr');
    var $gridview = $(this).closest('.gridview');
    var $operations = $gridview.find('.operations button');

    if ($(this).is(':checked')) {
      $checks.addClass('active');
      $operations.removeClass('disabled');
    } else {
      $checks.removeClass('active');
      $operations.addClass('disabled');
    }
  });

  /**
   * Auto remove
   * Usage:
   * <div class="auto-remove">…</div>
   */
  if ($('.auto-remove').length) {
    setTimeout(function() {
      $('.auto-remove').slideUp(300, function() {
        $(this).remove();
      });
    }, 3000);
  }

  /**
   * Cloning
   * Usage:
   * <div class="clone-block">
   *   <div class="clone-item">Item</div>
   *     <a class="clone">Add</a>
   *   </div>
   * </div>
   */
  $('.clone-block').on('click', '.clone', function() {
    var $block = $(this).closest('.clone-block');
    $block.find('.clone-item:eq(0)').clone().show().insertAfter($block.find('.clone-item:last'));
  });

  /**
   * Remove an item
   * Usage:
   * <li>
   *  Item
   *  <a class="remove-item" data-remove-item="li">x</a>
   * </li>
   */
  $(document).on('click', '.remove-item', function() {
    $(this).closest($(this).data('remove-item')).remove();
  });
});
