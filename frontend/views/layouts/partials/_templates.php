<script id="single-upload-media-template" type="text/x-handlebars-template">
   <div class="cop-form--container">
                            <div class="cop-form__uploader design2 design2--auto">
                              <span class="cop-form__uploader--btn"><i class="fa fa-arrow-up"></i></span>
                              <p class="cop-form__uploader--text"><span>Upload Certificate</span></p>
                              <button type="button" class="trash" data-id="{{id}}" data-unqid="{{unqid}}" data-guid="{{guid}}"><i class="fa fa-trash-alt"></i></button>
                              <div class="cop-form__uploader--placeholder">
                         {{#if image}}
                    <img src="{{media}}" class="rounded-circle" alt="image" />
                        {{/if}}
                {{#if pdf}}
            <img src="<?= \Yii::$app->params['staticHttpPath'] ?>/admin/dist/images/icons/upload-pdf.svg" class="rounded-circle" alt="image" />
        {{/if}}
        {{#if doc}}
            <img src="<?= \Yii::$app->params['staticHttpPath'] ?>/admin/dist/images/icons/upload-doc.svg" class="rounded-circle" alt="image" />
        {{/if}}
        {{#if docx}}
            <img src="<?= \Yii::$app->params['staticHttpPath'] ?>/admin/dist/images/icons/upload-doc.svg" class="rounded-circle" alt="image" />
        {{/if}}
        {{#if xls}}
            <img src="<?= \Yii::$app->params['staticHttpPath'] ?>/admin/dist/images/icons/upload-doc.svg" class="rounded-circle" alt="image" />
        {{/if}}
                              </div>
                            </div>
 </div>
</script>
<script id="upload-media-template" type="text/x-handlebars-template">
    <div class="owl-item">
        <div class="item">
            <a href="javascript:;">
                <div class="overlay_icons">
                    <div class="close removeImage" data-id="{{id}}"  data-unqid="{{unqid}}"><i class="fa fa-close"></i></div>
                </div>
                <img src="{{media}}" alt="image" />
            </a>
        </div>
    </div>
</script>
<script id="icon-upload-media-template" type="text/x-handlebars-template">
    <div class="uploads__image">
        {{#if image}}
            <img src="{{media}}" alt="image" />
        {{/if}}
        <div class="uploads__image-close removeImage" data-id="{{id}}" data-unqid="{{unqid}}"><i class="fa fa-close"></i></div>
        <div class="uploads__image-content">{{file}}</div>
    </div>
</script>
