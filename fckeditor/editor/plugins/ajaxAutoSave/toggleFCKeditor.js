/*
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * File Name: toggleFCKeditor.js
 * toggleFCKeditor function
 * For more information on the script, see http://www.saulmade.nl/FCKeditor/FCKSnippets.php
 * 
 * File Authors:
 * 		Paul Moers (http://www.saulmade.nl, http://www.saulmade.nl/FCKeditor/FCKPlugins.php)
 *
 * Special thanks to Paul York for supporting me!
*/

	// config

	// what do we do with the toolbar when disabling the editor. Possibilities are 'disable', 'hide', 'collapse'.
	// When collapsed the toolbar can be expanded again by the user, but he'll find a disabled toolbar.
	var toolbarDisabledState = "disable";

	function toggleFCKeditor(editorInstance)
	{
		if ((!document.all && editorInstance.EditorDocument.designMode.toLowerCase() != "off") || (document.all && editorInstance.EditorDocument.body.disabled == false))
		{
			// disable the editArea
			if (document.all)
			{
				editorInstance.EditorDocument.body.disabled = true;
			}
			else
			{
				editorInstance.EditorDocument.designMode = "off";
			}
			// disable the toolbar
			switch (toolbarDisabledState)
			{
				case "collapse" :		editorInstance.EditorWindow.parent.FCK.ToolbarSet._ChangeVisibility(true);
				case "disable" :		editorInstance.EditorWindow.parent.FCK.ToolbarSet.Disable();
											buttonRefreshStateClone = editorInstance.EditorWindow.parent.FCKToolbarButton.prototype.RefreshState;
											specialComboRefreshStateClone = editorInstance.EditorWindow.parent.FCKToolbarSpecialCombo.prototype.RefreshState;
											editorInstance.EditorWindow.parent.FCKToolbarButton.prototype.RefreshState = function(){return false;};
											editorInstance.EditorWindow.parent.FCKToolbarSpecialCombo.prototype.RefreshState = function(){return false;};
											break;
					case "hide" :		if (editorInstance.EditorWindow.parent.document.getElementById("xExpanded").style.display != "none")
											{
												editorInstance.EditorWindow.parent.document.getElementById("xExpanded").isHidden = true;
												editorInstance.EditorWindow.parent.document.getElementById("xExpanded").style.display = "none";
											}
											else
											{
												editorInstance.EditorWindow.parent.document.getElementById("xCollapsed").style.display = "none";
											}
											break;
			}
		}
		else
		{
			// enable the editArea
			if (document.all)
			{
				editorInstance.EditorDocument.body.disabled = false;
			}
			else
			{
				editorInstance.EditorDocument.designMode = "on";
			}
			// enable the toolbar
			switch (toolbarDisabledState)
			{
				case "collapse" :		editorInstance.EditorWindow.parent.FCK.ToolbarSet._ChangeVisibility(false);
				case "disable" :		editorInstance.EditorWindow.parent.FCK.ToolbarSet.Enable();
											editorInstance.EditorWindow.parent.FCKToolbarButton.prototype.RefreshState = buttonRefreshStateClone;
											editorInstance.EditorWindow.parent.FCKToolbarSpecialCombo.prototype.RefreshState = specialComboRefreshStateClone;
											break;
					case "hide" :		if (editorInstance.EditorWindow.parent.document.getElementById("xExpanded").isHidden == true)
											{
												editorInstance.EditorWindow.parent.document.getElementById("xExpanded").isHidden = false;
												editorInstance.EditorWindow.parent.document.getElementById("xExpanded").style.display = "";
											}
											else
											{
												editorInstance.EditorWindow.parent.document.getElementById("xCollapsed").style.display = "";
											}
											break;
			}
			// set focus on editorArea
			editorInstance.EditorWindow.focus();
			// and update toolbarset
			editorInstance.EditorWindow.parent.FCK.ToolbarSet.RefreshModeState();
		}
	}











