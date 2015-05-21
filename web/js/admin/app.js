var nprogress = require('nprogress');

var app = {
  init: function() {
    this.ajaxSetup();
    this.binding();
  },

  ajaxSetup: function() {
    $.ajaxSetup({
      type: 'POST',
      dataType: 'json'
    });

    $(document).ajaxSend(function(event, jqXHR, settings) {
      nprogress.start();
    });

    $(document).ajaxComplete(function(event, jqXHR, settings) {
      nprogress.done();
    });

    $(document).ajaxError(function(event, jqxhr, settings, exception) {
      console.log(jqxhr.responseText);
    });
  },

  binding: function() {
    // Menu
    $('.sidebar').on('click', 'a', function() {
      if ($(this).next('ul').length) {
        $('.sidebar li .nav').hide();
        $(this).next('.nav').toggle();
      }
    });

    // Confirmation
    $(document).on('click', '.confirmation', function(e) {
      if (!confirm($(this).data('confirmation'))) {
        e.stopImmediatePropagation();
        return false;
      }
    });

    // Gridview: Ajax submit
    $(document).on('click', '.gridview .submit', function(e) {
      var _this = this;
      var $form = $(this).closest('form');

      $.post(
        $(_this).prop('href') ? $(_this).prop('href') : $form.prop('action'),
        $form.serialize() + '&' + $(_this).prop('name') + '=' + $(_this).val(),
        function(data) {
          $(_this).closest('.gridview').yiiGridView('applyFilter');
          return false;
        }

      );

      return false;
    });

    // Gridview: Checkbox
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

    // Gridview: Check all
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
     * Auto remove.
     */
    if ($('.auto-remove').length) {
      setTimeout(function() {
        $('.auto-remove').slideUp(300, function() {
          $(this).remove();
        });
      }, 3000);
    }

    /**
     * Cloning.
     * Usage:
     * <div class="clone-block">
     *   <div class="clone-item">Item</div>
     *     <a class="clone">Add</a>
     *   </div>
     *   …
     */
    $('.clone-block').on('click', '.clone', function() {
      var $block = $(this).closest('.clone-block');
      $block.find('.clone-item:eq(0)').clone().show().insertAfter($block.find('.clone-item:last'));
    });

    /**
     * Remove item.
     * Usage:
     * <li>Item<a class="remove-item" data-remove-item="li">X</a></li>
     */
    $(document).on('click', '.remove-item', function() {
      $(this).closest($(this).data('remove-item')).remove();
    });
  }
}

module.exports = app;
