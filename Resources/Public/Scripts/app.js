$(function() {
  var flashMessagesCookie = Cookies.get('Neos_Flow_FlashMessages');
  if (!flashMessagesCookie) {
      return;
  }
  var flashMessages = JSON.parse(flashMessagesCookie);
  for (var i in flashMessages) {
      var className = null;
      if (flashMessages[i].severity == 'Notice') {
          className = 'alert-info';
      } else if (flashMessages[i].severity == 'Warning') {
          className = 'alert-warning';
      } else if (flashMessages[i].severity == 'Error') {
          className = 'alert-danger';
      }
      bootbox.dialog({title: decodeURIComponent(flashMessages[i].title).replace(/\+/g, ' '), message: decodeURIComponent(flashMessages[i].body).replace(/\+/g, ' '), onEscape: true, className: className});
  }
  Cookies.remove('Neos_Flow_FlashMessages');
});