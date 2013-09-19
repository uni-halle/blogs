var shr_cdn_root = "https://dtym7iokkjlif.cloudfront.net";

function getBrowser() {
  var sUA = navigator.userAgent;
  var sName = "";
  if (sUA.indexOf("MSIE") != -1) {
    sName = "Internet Explorer";
  } else if (sUA.indexOf("Firefox") != -1) {
    sName = "Firefox";
  } else if (sUA.indexOf("Flock") != -1) {
    sName = "Flock";
  } else if (sUA.indexOf("Chrome") != -1) {
    sName = "Google Chrome";
  } else if (sUA.indexOf("Safari") != -1) {
    sName = "Safari";
  } else if (sUA.indexOf("Opera") != -1) {
    sName = "Opera";
  } else if (sUA.indexOf("Songbird") != -1) {
    sName = "Songbird";
  }
  return sName;
}

jQuery(window).load(function() {
  jQuery.cookie = function(d, c, a) {
    if (typeof c != "undefined") {
      a = a || {};
      if (c === null) c = "", a.expires = -1;
      var b = "";
      if (a.expires && (typeof a.expires == "number" || a.expires.toUTCString)) typeof a.expires == "number" ? (b = new Date, b.setTime(b.getTime() + a.expires * 864E5)) : b = a.expires, b = "; expires=" + b.toUTCString();
      var e = a.path ? "; path=" + a.path : "",
          f = a.domain ? "; domain=" + a.domain : "",
          a = a.secure ? "; secure" : "";
      document.cookie = [d, "=", encodeURIComponent(c), b, e, f, a].join("")
    } else {
      c = null;
      if (document.cookie && document.cookie != "") {
        a = document.cookie.split(";");
        for (b = 0; b < a.length; b++) if (e = jQuery.trim(a[b]), e.substring(0, d.length + 1) == d + "=") {
          c = decodeURIComponent(e.substring(d.length + 1));
          break
        }
      }
      return c
    }
  };

  var code = "";
  var ua = getBrowser();

  switch (ua) {
  case "Firefox":
    code = '<div id="ext-promo-prompt" class="fs_a fs_c_midgrey2"><img src="' + shr_cdn_root + '/media/images/firefox_32x32.png" height=24 width=24 align="absmiddle" style="margin: -4px 8px 0 8px;" />Get the <a href="https://addons.mozilla.org/en-US/firefox/addon/5457/" target="_new">shareaholic firefox extension</a> - It is the best way to <a href="https://addons.mozilla.org/en-US/firefox/addon/5457/" target="_new">share your content, discover, and connect with the Best of the Web</a>! <a class="close" href="javascript:ext_promo_noThanks();">x</a> <a class="install rounded_5" href="https://addons.mozilla.org/en-US/firefox/addon/5457/" target="_new">Install</a></div>';
    break;
  case "Google Chrome":
    code = '<div id="ext-promo-prompt" class="fs_a fs_c_midgrey2"><img src="' + shr_cdn_root + '/media/images/chrome_32x32.png" height=24 width=24 align="absmiddle" style="margin: -4px 8px 0 8px;" />Get the <a href="https://chrome.google.com/webstore/detail/kbmipnjdeifmobkhgogdnomkihhgojep" target="_new">shareaholic chrome extension</a> - It is the best way to <a href="https://chrome.google.com/webstore/detail/kbmipnjdeifmobkhgogdnomkihhgojep" target="_new">share your content, discover, and connect with the Best of the Web</a>! <a class="close" href="javascript:ext_promo_noThanks();">x</a> <a class="install rounded_5" href="https://chrome.google.com/webstore/detail/kbmipnjdeifmobkhgogdnomkihhgojep" target="_new">Install</a></div>';
    break;
  default:
  }

  setTimeout(function() {
    if (jQuery('.extLives').length == 0) {
      var extpromoPrompt = jQuery.cookie("no_cp");
      var inFrame = (window != window.top)
      if ((extpromoPrompt != 1) && !inFrame) {
        jQuery("body").prepend(code);
        // Margin out the admin bar by its exact height if the admin bar is present.
        if (jQuery('#wpadminbar').length != 0) {
          jQuery("#ext-promo-prompt").css("margin-top", jQuery("#wpadminbar").css("height"));

          // Also, remove the dead whitespace.
          jQuery("#ext-promo-prompt").css("margin-bottom", "-" + jQuery("#ext-promo-prompt").css("height"));
        }
        jQuery('div#ext-promo-prompt').show();
      }
    }
  }, 500);
});

function ext_promo_noThanks() {
  jQuery.cookie('no_cp', '1', {
    expires: 60,
    path: '/'
  });
  jQuery('div#ext-promo-prompt').hide();
}
