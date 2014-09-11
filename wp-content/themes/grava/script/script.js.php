<?php
/* include needed wp-functions
 * NOTE: maybe you have to change the path */
  require_once(dirname(__FILE__)."/../../../../wp-blog-header.php");
?>
window.addEvent('domready', function(){
  /* AJAX-SHIT!!!1!1elf This calls the ajax-comments.php instead of the
   * wp-comments-post.php if js is enabled, and posts comments by xhr.
   * ==================================================================== */
  if ($('commentform')) { 
    /* We have a commentform on the page and javascript is working - 
     * let's change the action path to the ajax-comments.php-script
     */
    url = '<?php bloginfo('stylesheet_directory'); ?>/script/ajax-comments.php'
    $('commentform').setProperty('action', url);
    
    $('commentform').addEvent('submit', function(e) {
      // Prevent the submit event
      new Event(e).stop();
      this.send({
        onFailure: function() {
          if (!$('commenterror')) {
            var error = new Element('li', {
              'id': 'commenterror'
            });
            error.setHTML('Dein Kommentar konnte leider nicht eingetragen werden, da ein Serverfehler vorzuliegen scheint.');
            error.injectBefore($('lastcommentsformli'));
          } else {
            $('commenterror').setHTML('Dein Kommentar konnte leider nicht eingetragen werden, da ein Serverfehler vorzuliegen scheint.');
          }
        },

        onSuccess: function() {
          // added a new comment sucessfully?
          if (this.response.text.contains('<li id="newcomment">')) {
            if ($('commenterror')) {
              $('commenterror').remove();
            }
            // empty comment and mcsp value
            $('comment').setProperty('value', '');
<? if ( function_exists('math_comment_spam_protection') && !user_ID) { ?>
            $('mcspvalue').setProperty('value', "");
<?php } ?>
            
            // set the new comment and a notification
            $('respond').setText('Dein Kommentar wurde eingetragen.');
            
            // if this is the first comment, the ol commentlist and the h3-header
            // don't exist and we have to create them and add them to the dom
            if (!$('commentlist')) {
              var newCommentsHeader = new Element('h3', {
                'id': 'comments'
              });
              newCommentsHeader.setHTML("Ein neuer Kommentar");
              newCommentsHeader.injectBefore($('respond'));
              
              var newCommentList = new Element('ol', {
                'id': 'commentlist'
              });
              newCommentList.injectAfter($('comments'));
            } 
            $('commentlist').setHTML($('commentlist').innerHTML + this.response.text)
            
            // add some nifty effects
            var scroll = new Fx.Scroll(window, {
              wait: false,
              duration: 600,
              transition: Fx.Transitions.Back.easeOut
            });
            scroll.toElement($('newcomment'));
            var commentFadeIn = $('newcomment').effects({wait: 600, duration: 1800});
            commentFadeIn.start({'opacity': [0,1]});
            
          } else {
            if (!$('commenterror')) {
              var error = new Element('li', {
                'id': 'commenterror'
              });
              error.setHTML(this.response.text);
              error.injectBefore($('lastcommentsformli'));
            } else {
              $('commenterror').setHTML(this.response.text);
            }
          }
        }
        });
    });
  }

  /* Add an infin symbol to all external links (here we need PHP) except
   * Those links containing an image. 
   * ==================================================================== */
  $$('a').each(function(el){
    /* Sometimes theres a bug with Opera and the Wordpress RTE
     * and attributes (HREF) become uppercase, so fix it here...
     */
    if (el.getProperty('href') === null) {
      if (el.getProperty('HREF') !== null) {
        ref = el.getProperty('HREF');
        el.removeProperty('HREF');
        el.setProperty('href', ref);
      } else {
        ref = '';
      }
    } else {
      var ref = el.getProperty('href');
    }
   
    if (ref.test('http://') || ref.test('https://') || ref.test('ftp://')) { // is an absolute link?
      if (!(ref.test("<?php echo get_option('home'); ?>"))) {
        var infin = new Element('span', {'class': 'externalURL'}).setHTML('&infin;');
        var imgInside = false;
        el.getChildren().each(function(i) {
          if (i.getTag() == 'img') {
            imgInside = true;
          }
        });
        if (!imgInside) {
          infin.injectBefore(el);
        }
      }
    }
  });
  
  /* Add a &raquo; to the current page or category
   * ==================================================================== */
   
  $$('#sidebar .current-cat', '#sidebar .current_page_item').each(function(el) {
    el.setHTML('&raquo; ' + el.innerHTML);
  });

  /* Make the Sidebar slideable and add the action to sidebartoggler
   * ==================================================================== */
  var sidebarSlide = new Fx.Slide('sidebarhideable', {mode: 'horizontal'});
  $('sidebartoggler').addEvent('click', function(e) {
    e = new Event(e);
    sidebarSlide.toggle();
    e.stop();
  });
  
  /* Hooray for Button-Hover-Images...
   * ==================================================================== */
  
  $$('.button').each(function(el) { 
    el.addEvents({
      'mouseover' :
        function() {
          el.setProperty('src', el.src.replace(/.png/g, '_hover.png'));
        },
      'mouseout' :
        function() {
          el.setProperty('src', el.src.replace(/_hover.png/g, '.png'));
        }
    });
  });
  
  /* Add Tooltips to the abbr, acronym and the Buttons at the top
   * ==================================================================== */
  if (!window.ie) {
    var toolTips = new Tips($$('#content .toggler', '.button', '#sidebar .linkcat a', 
                               '#searchsubmit', '#submit', 'abbr', 'acronym'), {
      initialize:function(){
        this.fx = new Fx.Style(this.toolTip, 'opacity', {duration: 600, wait: false}).set(0);
      },
      onShow: function(toolTip) {
        this.fx.start(1);
      },
      onHide: function(toolTip) {
        this.fx.start(0);
      }
    });
  }
  /* Add the Accordion for each post-entry, and the heading h2 a as toggler 
   * ==================================================================== */
  var accordion = new Accordion('#content .toggler', '#content .entry', {
    transition: Fx.Transitions.Back.easeOut,
    duration: 1200,
    opacity: false,
    show: 0,
    onActive: function(toggler, element){
      toggler.setStyle('color', '#222');
      /* Strange Firefox-Bugfix */
      element.setStyle('width', '600px');
    },
    onBackground: function(toggler, element){
      element.setStyle('height', '0');
    }
  }, $('content'));

  /* Adjust the min-height of the content to be slightly higher (+40px)
   * than the current height of the sidebarcontainer and add smoothscroll
   * ==================================================================== */
   
  var sideHeight = $('sidebarcontainer').getSize().size.y + 40;
  $('content').setStyles('min-height: ' + sideHeight + 'px; height: auto !important; height: ' + sideHeight + 'px;');
  
  new SmoothScroll();

});