CKEDITOR.plugins.add('uploadimage', {
    icons: 'uploadimage',
    init: function (editor) {
        editor.addCommand('callUploadImage', {
            exec: function (editor) {
                var ckeditor = editor;
                $.fn.upload($('#themeModal'), {
                    containerId: "_ckeditor",
                    maxFilesize: 5,
                    uploadToModule: 'custom',
                    showGallery: true,
                    uploadMultiple: false,
                    addRemoveLinks : true,
                    acceptedFiles : "image/*",
                    onSuccess : function (file, response) {
                        var mediaId = response.media['orig'];
                        var mediaUrl = response.media['cdnPath'];
            
                        ckeditor.insertHtml('<img class="img-responsive" data-media-id="' + mediaId + '" src="' + mediaUrl + '"/>');
                    },
                    'onError': function (obj, errorMessage) {
                        $().General_ShowErrorMessage({message: errorMessage});
                    },
                    'beforeSend': function (obj) {
                    },
                    'onComplete': function (obj) {
                    }
                });
            }
        });
        editor.ui.addButton('UploadImage', {
            label: 'Upload Image',
            command: 'callUploadImage',
            toolbar: 'insert'
        });
    }
});