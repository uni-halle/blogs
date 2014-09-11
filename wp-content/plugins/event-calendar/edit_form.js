/* EventCalendar.  $Revision: 255 $
 * Copyright (C) 2005 2006, Alex Tingle.
 * This file is licensed under the GNU GPL. See LICENSE file for details.
 */

/** Register an onload function. */
function WindowOnload(f)
{
  var prev=window.onload;
  window.onload=function(){ if(prev)prev(); f(); }
}

/***
 ***  Set elsewhere: Ec3EditForm.event_cat_id
 ***                 Ec3EditForm.start_of_week
 ***/

// namespace
function Ec3EditForm()
{
  var fmt="%Y-%m-%d %H:%M";

  WindowOnload( function()
  {
    var ELEMENT_NODE=1;
    var TEXT_NODE=3;

    Ec3EditForm.fieldset_se=document.getElementById('ec3_schedule_editor');
    Ec3EditForm.rows=document.getElementById('ec3_rows');
    Ec3EditForm.catinput=document.getElementById(Ec3EditForm.event_cat_id);
    
    if(Ec3EditForm.fieldset_se && Ec3EditForm.rows && Ec3EditForm.catinput)
    {
      // Perform some sleight of hand....
      // What I WANT to do, is set Ec3EditForm.catinput to read only, however
      // that's not possible for a checkbox. Disabled is good, but disabled
      // checkboxes are never submitted with the form. So, we HIDE the real
      // checkbox, and make a new dummy one that is shown but disabled.
      Ec3EditForm.dummyinput=Ec3EditForm.catinput.cloneNode(true);
      Ec3EditForm.dummyinput.id='ec3_dummy';
      Ec3EditForm.dummyinput.name='ec3_dummy';
      Ec3EditForm.dummyinput.disabled=true;
      Ec3EditForm.catinput.parentNode.insertBefore(
        Ec3EditForm.dummyinput, Ec3EditForm.catinput
      );
      Ec3EditForm.catinput.style.display='none';
      update_category();
      add_row_listeners(Ec3EditForm.fieldset_se);
    }
  } );

  function add_row_listeners(e)
  {
    var buttons=e.getElementsByTagName('button');
    for(var i=0; i<buttons.length; i++)
      if(buttons[i].id.indexOf('trigger_ec3_')==0)
      {
        input_id=buttons[i].id.substr(8);
        input=document.getElementById(input_id);
        if(!input)
          continue;
        cal=Calendar.setup({
          inputField:input,
          ifFormat:fmt,
          showsTime:true,
          button:buttons[i].id,
          step:1,
          firstDay:Ec3EditForm.start_of_week,
          singleClick:false,
          onUpdate:cal_changed
        });
        try{
          input.setAttribute('ec3_date',
            Date.parseDate(input.value,fmt).getTime());
        } catch(e) {
          input.setAttribute('ec3_date',0);
        }
        firetree_addEvent(input,'change',on_changed);
      }
  }

  function update_category()
  {
    // Set both the real AND dummpy checkboxes to match Ec3EditForm.rows.
    if(Ec3EditForm.rows.value>0)
    {
      Ec3EditForm.catinput.checked=true;
      Ec3EditForm.dummyinput.checked=true;
    }
    else
    {
      Ec3EditForm.catinput.checked=false;
      Ec3EditForm.dummyinput.checked=false;
    }
  }

  Ec3EditForm.add_row=function()
  {
    // Get the second-from-last TR
    var trs=Ec3EditForm.fieldset_se.getElementsByTagName('tr');
    var tr=trs[trs.length-2];
    // Make a new row, based on it & add it into the table.
    var new_tr=tr.cloneNode(1);

    var selects=new_tr.getElementsByTagName('select'); 
    for(var i=0; i<selects.length; i++)
    {
      selects[i].name+=trs.length-3;
      selects[i].id=selects[i].name;
    }

    var inputs=new_tr.getElementsByTagName('input');                         
    for(var i=0; i<inputs.length; i++)
    {
      inputs[i].name+=trs.length-3;
      inputs[i].id=inputs[i].name;
    }

    var buttons=new_tr.getElementsByTagName('button');                         
    for(var i=0; i<buttons.length; i++)
    {
      buttons[i].id+=trs.length-3;
    }

    new_tr.style.display=trs[0].style.display;
    tr.parentNode.insertBefore(new_tr,tr);
    Ec3EditForm.rows.value++;
    update_category();
    add_row_listeners(new_tr);
  }

  Ec3EditForm.del_row=function(element)
  {
    tr=element.parentNode.parentNode.parentNode;
    var inputs=tr.getElementsByTagName('input');
    for(var i=0; i<inputs.length; i++)
      if(0 == inputs[i].name.indexOf('ec3_action_'))
      {
        inputs[i].value='delete';
        break;
      }
    tr.style.display='none';
    Ec3EditForm.rows.value--;
    update_category();
  }
  
  function on_changed(e)
  {
    var input;
    if(e.currentTarget)
        input=e.currentTarget; // Mozilla/Safari/w3c
    else if(window.event)
        input=window.event.srcElement; // IE
    else
        return;
    input_changed(input);
  }

  function input_changed(input)
  {
    if(input.id.indexOf('ec3_start_')>=0)
    {
      // Change the end time to preserve the event duration.
      var start=input;
      var end=document.getElementById(start.id.replace('_start_','_end_'));
      var start_date0=parseInt(start.getAttribute('ec3_date'));
      var end_date0=parseInt(end.getAttribute('ec3_date'));
      var start_date1=Date.parseDate(start.value,fmt).getTime();
      start.setAttribute('ec3_date',start_date1);
      var delta=start_date1 - start_date0;
      var end_date1=end_date0+delta;
      end.setAttribute('ec3_date',end_date1);
      end.value=(new Date(end_date1)).print(fmt);
    }
    else if(input.id.indexOf('ec3_end_')>=0)
    {
      // Make sure that the start time is before the end time.
      var start=document.getElementById(input.id.replace('_end_','_start_'));
      var end=input;
      var start_date0=parseInt(start.getAttribute('ec3_date'));
      var end_date0=parseInt(end.getAttribute('ec3_date'));
      var end_date1=Date.parseDate(end.value,fmt).getTime();
      end.setAttribute('ec3_date',end_date1);
      if(start_date0>end_date1)
      {
        var start_date1=end_date1;
        start.setAttribute('ec3_date',start_date1);
        start.value=(new Date(start_date1)).print(fmt);
      }
    }
  }
  
  function cal_changed(cal)
  {
    input_changed(cal.params.inputField);
  }

} // end namespace Ec3EditForm


// Export public functions from namespace.
Ec3EditForm();
