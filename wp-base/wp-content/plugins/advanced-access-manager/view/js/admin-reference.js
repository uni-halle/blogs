/*
  Copyright (C) <2011>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

jQuery(document).ready(function(){

    jQuery("#tree").treeview({
        collapsed: true,
        animated: "medium",
        control:"#sidetreecontrol",
        persist: "location"
    });

    jQuery('a').each(function(){
       if (jQuery(this).attr('href') == '#'){
           jQuery(this).bind('click', function(event){
               var link = jQuery(this).attr('link');
               jQuery('#content').empty().html(jQuery('.' + link).clone());
           })
       }
    });
});