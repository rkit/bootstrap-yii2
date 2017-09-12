<div id="<?= $selector; ?>" class="fileapi">
  <div class="btn btn-default js-fileapi-wrapper">
    <div class="fileapi-browse" data-fileapi="active.hide">
      <span class="glyphicon glyphicon-picture"></span>
      <span class="fileapi-browse-text">
        <?=Yii::t('app', 'Upload') ?>
      </span>
      <span data-fileapi="name"></span>
      <input type="file" name="<?= $inputName ?>">
    </div>
    <div class="fileapi-progress" data-fileapi="active.show">
      <div class="progress progress-striped">
        <div class="fileapi-progress-bar progress-bar progress-bar-info"
             data-fileapi="progress"
             role="progressbar"
             aria-valuemin="0"
             aria-valuemax="100"></div>
      </div>
    </div>
  </div><br>
  <?php if ($preview === true) : ?>
  <a href="#" class="fileapi-preview">
    <span data-fileapi="delete" class="fileapi-preview-delete">
      <span class="glyphicon glyphicon-trash"></span>
    </span>
    <span class="fileapi-preview-wrapper">
      <?php if (!empty($value)):?>
      <img src="<?= $value ?>">
      <?php endif?>
    </span>
  </a>

  <?php $this->registerJs("
  $(document).on('click', '#$selector [data-fileapi=\"delete\"]', function(evt) {
    evt.preventDefault();
    var file = $(this).closest('#$selector');
    file.fileapi('clear');
    file.find('input[type=\"hidden\"]').val('');
    file.find('.fileapi-preview-wrapper').empty();
  })"); ?>
  <?php endif; ?>

  <?= $input ?>

</div>
