/* EventCalendar. Copyright (C) 2005-2007, Alex Tingle.  $Revision: 284 $
 * This file is licensed under the GNU GPL. See LICENSE file for details.
 */

// Set in HTML file:
//   var ec3.start_of_week
//   var ec3.month_of_year
//   var ec3.month_abbrev
//   var ec3.myfiles
//   var ec3.home
//   var ec3.hide_logo
//   var ec3.viewpostsfor

/** Register an onload function. */
function WindowOnload(f)
{
  var prev=window.onload;
  window.onload=function(){ if(prev)prev(); f(); }
}

// namespace
function ec3()
{
  WindowOnload( function()
  {
    // Overwrite the href links in ec3_prev & ec3_next to activate EC3.
    var prev=document.getElementById('ec3_prev');
    var next=document.getElementById('ec3_next');
    if(prev && next)
    {
      // Check for cat limit in month link
      var xCat=new RegExp('&cat=[0-9]+$');
      var match=xCat.exec(prev.href);
      if(match)
        ec3.catClause=match[0];
      // Replace links
      prev.href='javascript:ec3.go_prev()';
      next.href='javascript:ec3.go_next()';
      // Pre-load image.
      ec3.imgwait=new Image(14,14);
      ec3.imgwait.src=ec3.myfiles+'/ec_load.gif';
      // Convert strings from PHP into Unicode
      ec3.viewpostsfor=unencode(ec3.viewpostsfor);
      for(var i=0; i<ec3.month_of_year.length; i++)
        ec3.month_of_year[i]=unencode(ec3.month_of_year[i]);
      for(var j=0; j<ec3.month_abbrev.length; j++)
        ec3.month_abbrev[j]=unencode(ec3.month_abbrev[j]);
    }
  } );

  /** Converts HTML encoded text (e.g. "&copy Copyright") into Unicode. */
  function unencode(text)
  {
    if(!ec3.unencodeDiv)
      ec3.unencodeDiv=document.createElement('div');
    ec3.unencodeDiv.innerHTML=text;
    return (ec3.unencodeDiv.innerText || ec3.unencodeDiv.firstChild.nodeValue);
  }

  function get_child_by_tag_name(element,tag_name)
  {
    var results=element.getElementsByTagName(tag_name);
    if(results)
      for(var i=0; i<results.length; i++)
        if(results[i].parentNode==element)
          return results[i];
    return 0;
  }
  ec3.get_child_by_tag_name=get_child_by_tag_name;


  function calc_day_id(day_num,month_num,year_num)
  {
    if(ec3.today_day_num==day_num &&
       ec3.today_month_num==month_num &&
       ec3.today_year_num==year_num)
    {
      return 'today';
    }
    else
    {
      return 'ec3_'+year_num+'_'+month_num+'_'+day_num;
    }
  }


  /** Replaces the caption and tbody in table to be the specified year/month. */
  function create_calendar(table_cal,month_num,year_num)
  {
    // Take a deep copy of the current calendar.
    var table=table_cal.cloneNode(1);

    // Calculate the zero-based month_num
    var month_num0=month_num-1;

    // Set the new caption
    var caption=get_child_by_tag_name(table,'caption');
    if(caption)
    {
      var c=get_child_by_tag_name(caption,'a');
      var caption_text=ec3.month_of_year[month_num0] + ' ' + year_num;
      if(c && c.firstChild && c.firstChild.nodeType==ec3.TEXT_NODE )
      {
	if(month_num<10) 
	{
	  c.href=ec3.home+'/?m='+year_num+'0'+month_num;
	}
	else
	{
	  c.href=ec3.home+'/?m='+year_num+month_num;
	}
        if(ec3.catClause)
           c.href+=ec3.catClause; // Copy cat' limit from original month link.
        c.title=ec3.viewpostsfor;
        c.title=c.title.replace(/%1\$s/,ec3.month_of_year[month_num0]);
        c.title=c.title.replace(/%2\$s/,year_num);
        c.firstChild.data=caption_text;
      }
    }

    if(caption && caption.firstChild && caption.firstChild.nodeType==ec3.TEXT_NODE)
      caption.firstChild.data=ec3.month_of_year[month_num0] + ' ' + year_num;

    var tbody=get_child_by_tag_name(table,'tbody');

    // Remove all children from the table body
    while(tbody.lastChild)
      tbody.removeChild(tbody.lastChild);

    // Make a new calendar.
    var date=new Date(year_num,month_num0,1, 12,00,00);

    var tr=document.createElement('tr');
    var td,div;
    tbody.appendChild(tr);
    var day_count=0
    var col=0;
    while(date.getMonth()==month_num0 && day_count<40)
    {
      var day=(date.getDay()+7-ec3.start_of_week)%7;
      if(col>6)
      {
        tr=document.createElement('tr');
        tbody.appendChild(tr);
        col=0;
      }
      if(col<day)
      {
        // insert padding
        td=document.createElement('td');
        td.colSpan=day-col;
        td.className='pad';
        tr.appendChild(td);
        col=day;
      }
      // insert day
      td=document.createElement('td');
      td.appendChild(document.createTextNode(date.getDate()));
      td.id=calc_day_id(date.getDate(),month_num,year_num);
      tr.appendChild(td);
      col++;
      day_count++;
      date.setDate(date.getDate()+1);
    }
    // insert padding
    if(col<7)
    {
      td=document.createElement('td');
      td.colSpan=7-col;
      td.className='pad';
      tr.appendChild(td);
    }

    // add the 'dog'
    if((7-col)>1 && !ec3.hide_logo)
    {
      a=document.createElement('a');
      a.href='http://blog.firetree.net/?ec3_version='+ec3.version;
      a.title='Event Calendar '+ec3.version;
      td.style.verticalAlign='bottom';
      td.appendChild(a);
      div=document.createElement('div');
      div.className='ec3_ec';
      div.align='right'; // keeps IE happy
      a.appendChild(div);
    }

    // set table's element id
    table.id='ec3_'+year_num+'_'+month_num;

    return table;
  } // end create_calendar()


  /** Dispatch an XMLHttpRequest for a month of calendar entries. */
  function loadDates(month_num,year_num)
  {
    var req=new XMLHttpRequest();
    if(req)
    {
      ec3.reqs.push(req);
      req.onreadystatechange=process_xml;
      req.open("GET",
        ec3.home+'/?ec3_xml='+year_num+'_'+month_num,true);
      set_spinner(1);
      req.send(null);
    }
  }
  

  /** Obtain an array of all the calendar tables. */
  function get_calendars()
  {
    var div=document.getElementById('wp-calendar');
    var result=new Array();
    for(var i=0; i<div.childNodes.length; i++)
    {
      var c=div.childNodes[i];
      if(c.id && c.id.search('ec3_[0-9]')==0 && c.style.display!='none')
        result.push(div.childNodes[i]);
    }
    if(result.length>0)
      return result;
    else
      return 0;
  }
  ec3.get_calendars=get_calendars;


  /** Changes the link text in the forward and backwards buttons.
   *  Parameters are the 0-based month numbers. */
  function rewrite_controls(prev_month0,next_month0)
  {
    var prev=document.getElementById('ec3_prev');
    if(prev && prev.firstChild && prev.firstChild.nodeType==ec3.TEXT_NODE)
      prev.firstChild.data='\u00ab\u00a0'+ec3.month_abbrev[prev_month0%12];
    var next=document.getElementById('ec3_next');
    if(next && next.firstChild && next.firstChild.nodeType==ec3.TEXT_NODE)
      next.firstChild.data=ec3.month_abbrev[next_month0%12]+'\u00a0\u00bb';
  }


  /** Turn the busy spinner on or off. */
  function set_spinner(on)
  {
    var spinner=document.getElementById('ec3_spinner');
    var publish=document.getElementById('ec3_publish');
    if(spinner)
    {
      if(on)
      {
        spinner.style.display='inline';
        if(publish)
          publish.style.display='none';
      }
      else
      {
        spinner.style.display='none';
        if(publish)
          publish.style.display='inline';
      }
    }
  }


  /** Called when the user clicks the 'previous month' button. */
  function go_prev()
  {
    var calendars=get_calendars();
    if(!calendars)
      return;
    var pn=calendars[0].parentNode;

    // calculate date of new calendar
    var id_array=calendars[0].id.split('_');
    if(id_array.length<3)
      return;
    var year_num=parseInt(id_array[1]);
    var month_num=parseInt(id_array[2])-1;
    if(month_num==0)
    {
      month_num=12;
      year_num--;
    }
    // Get new calendar
    var newcal=document.getElementById('ec3_'+year_num+'_'+month_num);
    if(newcal)
    {
      // Add in the new first calendar
      newcal.style.display=ec3.calendar_display;
    }
    else
    {
      newcal=create_calendar(calendars[0],month_num,year_num);
      pn.insertBefore( newcal, calendars[0] );
      loadDates(month_num,year_num);
    }
    // Hide the last calendar
    ec3.calendar_display=calendars[calendars.length-1].style.display;
    calendars[calendars.length-1].style.display='none';

    // Re-write the forward & back buttons.
    rewrite_controls(month_num+10,month_num+calendars.length-1);
  }
  ec3.go_prev=go_prev;


  /** Called when the user clicks the 'next month' button. */
  function go_next()
  {
    var calendars=get_calendars();
    if(!calendars)
      return;
    var pn=calendars[0].parentNode;
    var last_cal=calendars[calendars.length-1];

    // calculate date of new calendar
    var id_array=last_cal.id.split('_');
    if(id_array.length<3)
      return;
    var year_num=parseInt(id_array[1]);
    var month_num=1+parseInt(id_array[2]);
    if(month_num==13)
    {
      month_num=1;
      year_num++;
    }
    // Get new calendar
    var newcal=document.getElementById('ec3_'+year_num+'_'+month_num);
    if(newcal)
    {
      // Add in the new last calendar
      newcal.style.display=ec3.calendar_display;
    }
    else
    {
      newcal=create_calendar(calendars[0],month_num,year_num);
      if(last_cal.nextSibling)
        pn.insertBefore(newcal,last_cal.nextSibling);
      else
        pn.appendChild(newcal);
      loadDates(month_num,year_num);
    }
    // Hide the first calendar
    ec3.calendar_display=calendars[0].style.display;
    calendars[0].style.display='none';

    // Re-write the forward & back buttons.
    rewrite_controls(month_num-calendars.length+11,month_num);
  }
  ec3.go_next=go_next;


  /** Triggered when the XML load is complete. Checks that load is OK, and then
   *  updates calendar days. */
  function process_xml()
  {
    var busy=0;
    for(var i=0; i<ec3.reqs.length; i++)
    {
      var req=ec3.reqs[i];
      if(req)
      {
        if(req.readyState==4)
        {
          ec3.reqs[i]=0;
          if(req.status==200)
            update_days(req.responseXML.documentElement);
        }
        else
          busy=1;
      }
    }
    if(!busy)
    {
      // Remove old requests.
      while(ec3.reqs.shift && ec3.reqs.length && !ec3.reqs[0])
        ec3.reqs.shift();
      set_spinner(0);
    }
  }


  /** Adds links to the calendar for each day listed in the XML. */
  function update_days(month_xml)
  {
    var days=month_xml.getElementsByTagName('day');
    if(!days)
      return;
    for(var i=0; i<days.length; i++)
    {
      var td=document.getElementById(days[i].getAttribute('id'));
      if(td && td.firstChild && td.firstChild.nodeType==ec3.TEXT_NODE)
      {
        td.className='ec3_postday';
        var txt=td.removeChild(td.firstChild);
        var a=document.createElement('a');
        a.href=days[i].getAttribute('link');
        a.title=days[i].getAttribute('titles');
        if(days[i].getAttribute('is_event'))
        {
          td.className+=' ec3_eventday';
          a.className='eventday';
        }
        a.appendChild(txt);
        td.appendChild(a);
      }
    }
    if(typeof ec3_Popup != 'undefined')
    {
      var month=
        document.getElementById(month_xml.childNodes[0].getAttribute('id'));
      if(month)
        ec3_Popup.add_tbody( get_child_by_tag_name(month,'tbody') );
    }
  }


} // end namespace ec3

// Export public functions from ec3 namespace.
ec3();

// Set up static variables in namespace 'ec3'.

// Get today's date.
// Note - DO THIS ONCE, so that the value of today never changes!
ec3.today=new Date();
ec3.today_day_num=ec3.today.getDate();
ec3.today_month_num=1+ec3.today.getMonth();
ec3.today_year_num=ec3.today.getFullYear();

// Holds ongoing XmlHttp requests.
ec3.reqs=new Array();

ec3.ELEMENT_NODE=1;
ec3.TEXT_NODE=3;

ec3.version='3.1.4';
