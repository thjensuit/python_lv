/*
 * improt.js
 * @author H.Yunoki
 * @version 1.0.0
 * @lastupdate 2009.11.17
 */

( function() {
  var imports_js_current_dir = find_current_dir();

  /* ここから  */
  require('common-libs.js');
  require('common-list.js');
  require('input_control.js');
  require('jquery.colorbox.js');
  require('item_control.js');

  /* ここまで  */

  function require(uri) {
    document.write('<script type="text/javascript" src="'+imports_js_current_dir+uri+'" charset="utf-8"></script>');
  }

  function require_css(uri) {
    document.write('<link rel="stylesheet" href="'+imports_js_current_dir+uri+'" type="text/css" />');
  }

  function find_current_dir(){
    var tmp_path;
    var t = document.getElementsByTagName('script');
    for ( var i in t ) {
      if ( tmp_path = new String( t[i].src ).match(/(.*\/admin\/)import.js$/) ) {
        return tmp_path[1];
      }
    }
  }

})();
