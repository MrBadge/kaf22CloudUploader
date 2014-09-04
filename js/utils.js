function createAutoClosingAlert(selector, delay) {
   var alert = $(selector);
   window.setTimeout(function() { alert.alert('close') }, delay);
}