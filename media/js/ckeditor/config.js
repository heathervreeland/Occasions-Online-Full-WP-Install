/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	//CKEDITOR.config.removePlugins = 'elementspath';
	config.enterMode = CKEDITOR.ENTER_P;
	config.forceEnterMode = true;
	config.forcePasteAsPlainText = true;
	config.resize_dir = 'vertical';
	config.resize_minHeight = 200;
	config.resize_maxHeight = 600;
	config.toolbarCanCollapse = false;
	config.coreStyles_bold 		= { element : 'b', overrides : 'strong' };
	config.coreStyles_italic 	= { element : 'i', overrides : 'em' };
	config.coreStyles_underline	= { element : 'u' };
	config.coreStyles_strike 	= { element : 'strike' };
	config.toolbar = 
					[
						['Undo','Redo'],
						['Bold', 'Italic', 'Strike', 'Underline', '-', 'NumberedList', 'BulletedList', '-', 'Link', 'Unlink'],
						['SelectAll','RemoveFormat','Source']
					];
};
