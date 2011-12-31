/* Ask descriptions logic */

function setCookie(sName, sValue) { //для всего домена: path=/
  document.cookie = sName + "=" + escape(sValue) + "; expires=Thu, 1 Jan 2050 20:47:11 UTC; path=/";
}

function getCookie(name) {
  var cookie = " " + document.cookie;
  var search = " " + name + "=";
  var setStr = null;
  var offset = 0;
  var end = 0;
  if (cookie.length > 0) {
    offset = cookie.indexOf(search);
    if (offset != -1) {
      offset += search.length;
      end = cookie.indexOf(";", offset)
      if (end == -1) {
        end = cookie.length;
      }
      setStr = unescape(cookie.substring(offset, end));
    }
  }
  return(setStr);
}

// центрируем окно опроса в видимой части окна страницы
function moveAsk() {
    var posLeft = ($(window).width()-$('#ask_subscript').width())/2;
    var posTop = ($(window).height()- $('#ask_subscript').height())/2 + $(window).scrollTop();
    $('#ask_subscript').css('left', posLeft);
    $('#ask_subscript').css('top', posTop);
}


