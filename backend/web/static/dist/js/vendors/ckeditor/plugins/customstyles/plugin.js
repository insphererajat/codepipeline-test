CKEDITOR.plugins.add('customstyles', {
    onLoad: function () {
        CKEDITOR.addCss(
            'figure {' +
                'margin:10px 0px;' +
            '}' 
        );
    }
});