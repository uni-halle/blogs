function getFlashMovie(movieName)
{
  if (window.document[movieName]) 
  {
      return window.document[movieName];
  }
  if (navigator.appName.indexOf("Microsoft Internet")==-1)
  {
     if (document.embeds && document.embeds[movieName])
     {
        return document.embeds[movieName]; 
     }
  }
  else // if (navigator.appName.indexOf("Microsoft Internet")!=-1)
  {
     return document.getElementById(movieName);
  }
}

function callStudyLogSortChronological(date) {
   getFlashMovie("flashui").callStudyLogSortChronological(date);
}
function callStudyLogSortAlphabetical(letter) {	
   if(letter==null)
   {
      getFlashMovie("flashui").callStudyLogSortAlphabeticalDefault();
   }
   else
   {
      getFlashMovie("flashui").callStudyLogSortAlphabetical(letter);
   }	
}
function callStudyLogSortDefault() {
    getFlashMovie("flashui").callStudyLogSortDefault();
}
function callStudyLogSortByTagId(tag) {
    getFlashMovie("flashui").callStudyLogSortByTagId(tag);
}
function callStudyLogSortByTagName(tag) {	
    tb_remove();
	getFlashMovie("flashui").callStudyLogSortByTag(tag);
}
function callStudyLogSortBySearch(query) {
    getFlashMovie("flashui").callStudyLogSortBySearch(query);
}
function callStudyLogTagList() {
    getFlashMovie("flashui").callStudyLogTagList();
}
function callStudyLogAddBlockActivitySprite() {
    getFlashMovie("flashui").addBlockActivitySprite();
}
function callStudyLogRemoveBlockActivitySprite() {
    getFlashMovie("flashui").removeBlockActivitySprite();
}