/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */
CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // http://docs.ckeditor.com/#!/api/CKEDITOR.config

    // The toolbar groups arrangement, optimized for two toolbar rows.
    config.toolbarGroups = [
        {name: 'clipboard', groups: ['clipboard', 'undo']},
        {name: 'editing', groups: ['find', 'selection', 'spellchecker']},
        {name: 'links'},
        {name: 'insert'},
        {name: 'forms'},
        {name: 'tools'},
        {name: 'document', groups: ['mode', 'document', 'doctools']},
        {name: 'others'},
        '/',
        {name: 'basicstyles', groups: ['basicstyles', 'cleanup']},
        {name: 'paragraph', groups: ['list', 'indent', 'blocks', 'align', 'bidi']},
        {name: 'styles'},
        {name: 'colors'},
        {name: 'about'}
    ];

    // Remove some buttons provided by the standard plugins, which are
    // not needed in the Standard(s) toolbar.
    config.removeButtons = 'Underline,Subscript,Superscript';

    // Set the most common block elements.
    config.format_tags = 'p;h1;h2;h3;pre';

    // Simplify the dialog windows.
    config.removeDialogTabs = 'image:advanced;link:advanced';

//        //Allowed Content
//        config.allowedContent = {
//            img: {
//                attributes: [ '!src', 'alt', 'class' ]
//            }
//        };

    //Extra Plugins
    config.extraPlugins = 'justify';

    //Extra Allowed Content
    config.allowedContent = true;
    config.protectedSource.push(/<a[^>]*><\/a>/g);
    config.extraAllowedContent = '*(*);div(*);iframe[*]';

    //File Browse URL
    // config.filebrowserBrowseUrl = asset_domain + '/elfinder/media';
    // config.filebrowserImageBrowseUrl = asset_domain + '/elfinder/media';
//	config.filebrowserFlashBrowseUrl = BASE_URL + '/assets/plugins/kcfinder/browse.php?cms=CodeIgniter&type=flash';
//	config.filebrowserUploadUrl = BASE_URL + '/assets/plugins/kcfinder/upload.php?cms=CodeIgniter&type=files';
//	config.filebrowserImageUploadUrl = BASE_URL + '/assets/plugins/kcfinder/upload.php?cms=CodeIgniter&type=images';
//	config.filebrowserFlashUploadUrl = BASE_URL + '/assets/plugins/kcfinder/upload.php?cms=CodeIgniter&type=flash';

//Extra Headers
    config.fileTools_requestHeaders = {
        'Access-Control-Allow-Origin': '*'
    };
};
