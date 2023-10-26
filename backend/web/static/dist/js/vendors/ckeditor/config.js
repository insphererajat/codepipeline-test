/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';

	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_BR;
	config.autoParagraph = false;
};
CKEDITOR.timestamp = '2017092204'; //update this in ckeditor.js

CKEDITOR.dtd.$removeEmpty.span = false; 
CKEDITOR.dtd.$removeEmpty.i = false;



// config.enterMode = CKEDITOR.ENTER_P; // inserts <p></p>
// config.enterMode = CKEDITOR.ENTER_BR; // inserts <br />