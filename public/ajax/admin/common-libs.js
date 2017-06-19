
var rand_str = function (len) {
  var source = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-';
  var result = '';
  for ( var i = 0; i < len; i++ ) {
    result += source.charAt( Math.floor( Math.random() * source.length ) );
  }
  return result;
}

var read_option = function ( my_option, initial ) {
  if ( !my_option ) my_option = initial;
  return  my_option.split( /[, ]+/ );
}

