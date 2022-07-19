export const setCookie = (c_name, value, exdays) => {
  var exdate = new Date();
  exdate.setDate(exdate.getDate() + exdays);
  var c_value =
    encodeURI(value) + (exdays == null ? '' : '; expires=' + exdate.toUTCString());
  document.cookie = c_name + '=' + c_value;
};

export const getCookie = (c_name) => {
  var i,
    x,
    y,
    ARRcookies = document.cookie.split(';');
  for (i = 0; i < ARRcookies.length; i++) {
    x = ARRcookies[i].substring(0, ARRcookies[i].indexOf('='));
    y = ARRcookies[i].substring(ARRcookies[i].indexOf('=') + 1);
    x = x.replace(/^\s+|\s+$/g, '');
    if (x == c_name) {
      return decodeURI(y);
    }
  }
};

export const eraseCookie = (name) => {   
  document.cookie = name+'=; Max-Age=-99999999;';  
}