<div id="<?= $selector; ?>" class="fileapi">
  <div class="btn btn-default js-fileapi-wrapper">
    <div class="fileapi-browse" data-fileapi="active.hide">
      <span class="glyphicon glyphicon-picture"></span>
      <span data-fileapi="browse-text" class="<?= $value ? 'hidden' : 'browse-text' ?>">
        <?= $title ?>
      </span>
      <span data-fileapi="name"></span>
      <input type="file" name="<?= $paramName ?>">
    </div>
    <div class="fileapi-progress" data-fileapi="active.show">
      <div class="progress progress-striped">
        <div class="fileapi-progress-bar progress-bar progress-bar-info" data-fileapi="progress"
        role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
      </div>
    </div>
  </div><br>
  <?php if ($preview === true) : ?>
  <a href="#" class="fileapi-preview">
    <span data-fileapi="delete" class="fileapi-preview-delete">
      <span class="glyphicon glyphicon-trash"></span></span>
      <span data-fileapi="preview" class="fileapi-preview-wrapper"></span>
    </a>

  <?php $this->registerJs(
  "$(document).on('click', '#$selector [data-fileapi=\"delete\"]', function(evt) {" .
    "evt.preventDefault();" .
    "var file = $(this).closest('#$selector');" .
    "file.fileapi('clear');" .
    "file.find('[data-fileapi=\"browse-text\"]').removeClass('hidden');" .
    "file.find('input[type=\"hidden\"]').val('');" .
    "})"
  ); ?>
  <?php endif; ?>

  <?= $input ?>

  <?php if ($crop === true) : ?>
  <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Edit') ?></h4>
        </div>
        <div class="modal-body">
          <div class="crop-area"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
          <button type="button" class="btn btn-primary crop-save"><?= Yii::t('app', 'Save') ?></button>
        </div>
      </div>
    </div>
  </div>
  <?php endif; ?>

</div>
