/**
 *     Anything Popup
 *     Copyright (C) 2011 - 2015 www.gopiplus.com
 *     http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/
 * 
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 * 
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 * 
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

function _pop_submit()
{
	if((document.pop_form.pop_width.value=="") || isNaN(document.pop_form.pop_width.value))
	{
		alert("Please enter the popup window width, only number.")
		document.pop_form.pop_width.focus();
		return false;
	}
	else if((document.pop_form.pop_height.value=="") || isNaN(document.pop_form.pop_height.value))
	{
		alert("Please enter the popup window height, only number.")
		document.pop_form.pop_height.focus();
		return false;
	}
	else if((document.pop_form.pop_headercolor.value==""))
	{
		alert("Please enter the header color.")
		document.pop_form.pop_headercolor.focus();
		return false;
	}
	else if((document.pop_form.pop_bordercolor.value==""))
	{
		alert("Please enter the border color.")
		document.pop_form.pop_bordercolor.focus();
		return false;
	}
	else if((document.pop_form.pop_header_fontcolor.value==""))
	{
		alert("Please enter the heder font color.")
		document.pop_form.pop_header_fontcolor.focus();
		return false;
	}
	else if((document.pop_form.pop_title.value==""))
	{
		alert("Please enter the popup title.")
		document.pop_form.pop_title.focus();
		return false;
	}
	else if((document.pop_form.pop_caption.value==""))
	{
		alert("Please enter the popup link text/image.")
		document.pop_form.pop_caption.focus();
		return false;
	}
	else if(document.pop_form.pop_content.value=="")
	{
		alert("Please enter the popup content.")
		document.pop_form.pop_content.focus();
		return false;
	}
}

function _pop_delete(id)
{
	if(confirm("Do you want to delete this record?"))
	{
		document.frm_pop_display.action="options-general.php?page=anything-popup&ac=del&did="+id;
		document.frm_pop_display.submit();
	}
}	

function _pop_redirect()
{
	window.location = "options-general.php?page=anything-popup";
}

function _pop_help()
{
	window.open("http://www.gopiplus.com/work/2012/05/25/wordpress-popup-plugin-anything-popup/");
}