/*
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * File Name: fckplugin.js
 * 	Plugin to post the editor's cotent to the server through AJAX 
 * 
 * File Authors:
 * 		Mike Tonks (http://greenmap.sourceforge.net/fck_autosave.html)
 *              (adapted from ajaxPost by)
 *              Paul Moers (http://www.saulmade.nl, http://www.saulmade.nl/FCKeditor/FCKPlugins.php)
 * */


// ajaxAutoSaveObject constructor
var ajaxAutoSaveToolbarCommand = function()
{
	var tempNode;

	// include plugin's javascript
	tempNode = document.createElement("script");
	tempNode.type = "text/javascript";
	tempNode.src = FCKConfig.PluginsPath + "ajaxAutoSave/ajaxAutoSave.js";
	document.getElementsByTagName("head")[0].appendChild(tempNode);

	// preload toolbar loading image
	tempNode = new Image(); 
	tempNode.src = FCKConfig.PluginsPath + "ajaxAutoSave/images/loadingSmall.gif";

	FCK.Events.AttachEvent( 'OnSelectionChange', callOnSelectionChange );
}

// Register the command
FCKCommands.RegisterCommand('ajaxAutoSave', new ajaxAutoSaveToolbarCommand());

// Create the toolbar  button
var ajaxAutoSaveButton = new FCKToolbarButton('ajaxAutoSave', FCKLang.ajaxAutoSaveNoChanges);
ajaxAutoSaveButton.IconPath = FCKPlugins.Items['ajaxAutoSave'].Path + 'images/ajaxAutoSaveClean.gif';
FCKToolbarItems.RegisterItem('ajaxAutoSave', ajaxAutoSaveButton);

// manage the plugins' button behavior
ajaxAutoSaveToolbarCommand.prototype.GetState = function()
{
	return FCK_TRISTATE_OFF;
}


// what do we do when the button is clicked
ajaxAutoSaveToolbarCommand.prototype.Execute = function()
{
	if (ajaxAutoSaveButton.DOMDiv) // fCKeditor 2.2-
	{
		toolbarButtonIcon = ajaxAutoSaveButton.DOMDiv.getElementsByTagName('IMG')[0];
	}
	else // FCKeditor 2.3+
	{
		toolbarButtonIcon = ajaxAutoSaveButton._UIButton.MainElement.getElementsByTagName('IMG')[0];
	}
	toolbarButtonIcon.src = FCKConfig.PluginsPath + "ajaxAutoSave/images/loadingSmall.gif";
	//toolbarButtonIcon.title = FCKLang.ajaxAutoSaveButtonTitle;

	// instantiate ajaxAutoSave Object
	ajaxAutoSaveObject = new AxpObject(FCK);
	// giving the Object a reference to the toolbar button icon
	ajaxAutoSaveObject.toolbarButtonIcon = toolbarButtonIcon;
	// save
	ajaxAutoSaveObject.post();

	// reset state
	FCK_ajaxAutoSaveIsDirty = false;
	FCK_ajaxAutoSaveCounter = 0;
	FCK_ajaxAutoSaveDraftSaved = true;
}

// declare a global variable to hold the state
var FCK_ajaxAutoSaveIsDirty = false;

// declare a counter to give us a few keystrokes leeway
var FCK_ajaxAutoSaveCounter = 0;

// declare a status flag so we know if a draft has been saved
var FCK_ajaxAutoSaveDraftSaved = true;

ajaxAutoSaveToolbarCommand.prototype.onSave = function()
{
	FCK_ajaxAutoSaveIsDirty = false;
	FCKConfig.ajaxAutoSaveBeforeUpdateEnabled = false;
	return true;
	
}

// what to do when the fckeditor content is changed
ajaxAutoSaveToolbarCommand.prototype.onSelectionChange = function()
{
	if (FCK_ajaxAutoSaveIsDirty) return false;

	if (ajaxAutoSaveButton.DOMDiv) // fCKeditor 2.2-
	{
		toolbarButtonIcon = ajaxAutoSaveButton.DOMDiv.getElementsByTagName('IMG')[0];
	}
	else // FCKeditor 2.3+
	{
		toolbarButtonIcon = ajaxAutoSaveButton._UIButton.MainElement.getElementsByTagName('IMG')[0];
	}
	toolbarButtonIcon.src = FCKConfig.PluginsPath + "ajaxAutoSave/images/ajaxAutoSaveDirty.gif";
	toolbarButtonIcon.title = FCKLang.ajaxAutoSaveButtonTitle;

	setTimeout("ajaxAutoSaveToolbarCommand.prototype.Execute();", FCKConfig.ajaxAutoSaveRefreshTime * 1000);

	FCK.LinkedField.form.onsubmit = ajaxAutoSaveToolbarCommand.prototype.onSave;

	FCK_ajaxAutoSaveIsDirty = true;

}


function callOnSelectionChange(editorInstance){

	FCK_ajaxAutoSaveCounter++;

	if (FCK_ajaxAutoSaveCounter > FCKConfig.ajaxAutoSaveSensitivity) {
		ajaxAutoSaveToolbarCommand.prototype.onSelectionChange();
	}
}

        
//window.onbeforeunload = confirmExit;  TAKEN OUT BY RJM

function confirmExit() {

	if (FCKConfig.ajaxAutoSaveBeforeUpdateEnabled) {
		if (FCK_ajaxAutoSaveDraftSaved) {
			return FCKLang.ajaxAutoSaveBeforeUpdateDraft;
		} else {
			if (FCK_ajaxAutoSaveCounter > FCKConfig.ajaxAutoSaveSensitivity) {
				return FCKLang.ajaxAutoSaveBeforeUpdate;
			}
		}
	}
}

