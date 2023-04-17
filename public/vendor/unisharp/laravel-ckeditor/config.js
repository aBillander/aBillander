/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here.
	// For complete reference see:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' },
		{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools' ] },
		{ name: 'others' },
		'/',
		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons provided by the standard plugins, which are
	// not needed in the Standard(s) toolbar.
	config.removeButtons = 'Underline,Subscript,Superscript';

	// Set the most common block elements.
	config.format_tags = 'p;h1;h2;h3;pre';

	// Simplify the dialog windows.
	config.removeDialogTabs = 'image:advanced;link:advanced';

	// Allow **ALL** tags
	// https://stackoverflow.com/questions/11686019/how-to-disable-ckeditor-removing-tags
	// https://stackoverflow.com/questions/15753956/ckeditor-strips-inline-attributes
	// 
	// https://ckeditor.com/docs/ckeditor4/latest/guide/dev_advanced_content_filter.html
	// https://ckeditor.com/docs/ckeditor4/latest/examples/acf.html
	// 
	// https://ckeditor.com/docs/ckeditor4/latest/features/colorbutton.html
	// https://ckeditor.com/cke4/addon/colorbutton
	config.allowedContent = true;
	config.extraAllowedContent = '*(*);*{*}';
	config.extraAllowedContent = 'div;span;ul;li;table;td;style;*[id];*(*);*{*}';
};
