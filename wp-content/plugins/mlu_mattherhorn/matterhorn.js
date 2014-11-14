/** testing */
jQuery(function($){
    $('.matterhorn-video').each(function(){
        var $m=$(this);$m.css('width','100%');
	$m.css('height',parseInt($m.width()/16*9)+'px');
    });
});
