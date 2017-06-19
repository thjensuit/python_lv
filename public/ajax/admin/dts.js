var obj1 = '#group1';
var obj2 = '#group2';

var update_select = function ( obj, text_array, value_array ) {
  var selected_value = obj.value ;
  obj.options.length ='';

  for ( var i=0 ; i < value_array.length ; i++ ) {
    var value = value_array[i];
    var text  = text_array[i];
    if ( !text ) text = value ;
    obj.options[i] = new Option( text, value );
  }

  obj.value = selected_value ;
  if ( ! obj.value  ) obj.value='' ;
}

jQuery(function () {

  var text_array = new Array();
  var value_array = new Array();
  for ( var i=0 ; i < json_group.length; i++ ) {
    text_array.push( json_group[i].text );
    value_array.push( json_group[i].value );
  }

  update_select( jQuery(obj1).get(0), text_array, value_array );

  var onchange1 = function() {
    jQuery.each(json_group, function(){
      if ( this.value != jQuery(obj1).val() ) return ;
      update_select( jQuery(obj2).get(0) , this.group.text, this.group.value );
    });
  }

  jQuery(obj1).change(onchange1);
  onchange1();

});
