(function() {
  /**
   * AJAX Setup
   */
  $(document)
    .ajaxSend(function() {
      NProgress.start();
    })
    .ajaxComplete(function() {
      NProgress.done();
    });

  /**
   * Menu toggle
   */
  $('.sidebar').on('click', 'a', function() {
    if ($(this).next('ul').length) {
      $('.sidebar li .nav').hide();
      $(this)
        .next('.nav')
        .toggle();
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
        $(_this)
          .closest('.gridview')
          .yiiGridView('applyFilter');
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
  $(
    document
  ).on('click', '.gridview tbody tr td input[type="checkbox"]', function() {
    if ($(this).is(':checked')) {
      $(this)
        .closest('tr')
        .addClass('active');
    } else {
      $(this)
        .closest('tr')
        .removeClass('active');
    }

    var $gridview = $(this).closest('.gridview');
    var $batch = $gridview.find('.batch button');

    if ($gridview.find('tbody input:checked').size()) {
      $batch.removeClass('disabled');
    } else {
      $batch.addClass('disabled');
    }
  });

  /**
   * Select all checkboxes in GridView
   * Usage:
   * GridView::widget(['options' => ['class' => 'gridview']])
   */
  $(document).on('click', '.gridview .select-on-check-all', function() {
    var $checks = $(this)
      .closest('table')
      .find('tbody input[type="checkbox"]')
      .closest('tr');
    var $gridview = $(this).closest('.gridview');
    var $batch = $gridview.find('.batch button');

    if ($(this).is(':checked')) {
      $checks.addClass('active');
      $batch.removeClass('disabled');
    } else {
      $checks.removeClass('active');
      $batch.addClass('disabled');
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
    $block
      .find('.clone-item:eq(0)')
      .clone()
      .show()
      .insertAfter($block.find('.clone-item:last'));
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
    $(this)
      .closest($(this).data('remove-item'))
      .remove();
  });
})();
