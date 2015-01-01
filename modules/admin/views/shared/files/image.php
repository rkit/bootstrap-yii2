<div id="<?= $selector; ?>" class="uploader">
    <div class="btn btn-default js-fileapi-wrapper">
        <div class="uploader-browse" data-fileapi="active.hide">
            <span class="glyphicon glyphicon-picture"></span>
            <span data-fileapi="browse-text" class="<?= $value ? 'hidden' : 'browse-text' ?>">
                <?= Yii::t('app', 'Upload') ?>
            </span>
            <span data-fileapi="name"></span>
            <input type="file" name="<?= $paramName ?>">
        </div>
        <div class="uploader-progress" data-fileapi="active.show">
            <div class="progress progress-striped">
                <div class="uploader-progress-bar progress-bar progress-bar-info" data-fileapi="progress"
                     role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </div><br>
    <?php if ($preview === true) : ?>
        <a href="#" class="uploader-preview">
            <span data-fileapi="delete" class="uploader-preview-delete">
            <span class="glyphicon glyphicon-trash"></span></span>
            <span data-fileapi="preview" class="uploader-preview-wrapper"></span>
        </a>
        
        <?php $this->registerJs(
            "$(document).on('click', '#$selector [data-fileapi=\"delete\"]', function(evt) {" .
                "evt.preventDefault();" .
                "var uploader = $(this).closest('#$selector');" .
                "uploader.fileapi('clear');" .
                "uploader.find('[data-fileapi=\"browse-text\"]').removeClass('hidden');" .
                "uploader.find('input[type=\"hidden\"]').val('');" .
            "})"
        ); ?>
    <?php endif; ?>
    
    <?= $input ?>
    
    <?php if ($crop === true) : ?>
    <div id="<?= $selector; ?>-crop" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel"><?= Yii::t('app', 'Edit') ?></h4>
                </div>
                <div class="modal-body">
                    <div id="crop-preview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Cancel') ?></button>
                    <button type="button" class="btn btn-primary crop-save"><?= Yii::t('app', 'Save') ?></button>
                </div>
            </div>
        </div>
    </div>
    
    <?php $this->registerJs('$(document).on("' . $selector . '-initialize", 
        function (e, el, jcropSettings) {
            $(el).find("#' . $selector . '-crop").modal("show");
            setTimeout(function () {
                $(el).find("#' . $selector . '-crop #crop-preview").cropper(jcropSettings);
            }, 700);
        }
    );'); ?>
    
    <?php $this->registerJs('$(document).on("click", "#' . $selector . '-crop .crop-save", 
        function() {
            $(this).closest(".uploader").fileapi("upload");
            $(this).closest(".modal").modal("hide");
        }
    );'); ?>
    
    <?php endif; ?>
    
</div>