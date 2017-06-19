( function( $ ){
  $(document).ready(function(){
    /* 小窓を開く */
    $('a.ow, a[ow_option]').click( function (){
      var opt = read_option( $(this).attr('ow_option'), '350,350' );
      var t =  $(this).attr('target') || 'sw';
      window.open( this.href, t, 'resizable=yes,scrollbars=yes,width='+opt[0]+',height='+opt[1]).focus();
      return false;
    });

    /* 小窓を開く */
    $('form.ow, form[ow_option]').submit( function (){
      var opt = read_option( $(this).attr('ow_option'), '350,350' );
      var t =  $(this).attr('target') || 'sw';
      window.open( '', t, 'resizable=yes,scrollbars=yes,width='+opt[0]+',height='+opt[1]).focus();
      this.target = 'sw';
    });

    /* 画面遷移なしで送信処理 */
    $('form.hw').submit( function (){

      var check = $(this).attr('check');
      var conf = $(this).attr('confirm');
      var success = $(this).attr('success');

      if ( check && !eval(check) ) return false;
      if ( conf && !confirm(conf) ) return false;

      $.ajax({
        url : this.action,
        data: $(this).serialize(),
        type: 'post',
        success : function(){
          if ( success  ) { alert(success); }
          else { document.location.reload(true); }
        } // success
      }); // $.ajax

      return false;
    });

    /* koukai切り替え(ajax) */
    $('a.hw').click( function (tmp_code){
      var $$ = $(this);
      var param = new String(this.href).match(/(.*?)\?(.*)/);
      $.ajax({
        url : param[1],
        data: param[2],
        type: 'post',
        success : function(text){
          if ( new String($$.html()).match(/公開/) || new String($$.html()).match(/非公開/)) {
            if ( text==0 ){
              $$.html('非公開');
              $$.removeClass('koukai-1');
              $$.addClass('koukai-0');
            }else {
              $$.html('公開');
              $$.removeClass('koukai-0');
              $$.addClass('koukai-1');
             }
          }else {
            document.location.reload(true) ;
          }
        }
      }); // $.ajax

      return false;
    });

    /* ストライプ */
    $("table.stripe tr:odd").addClass("odd");
    $("table.stripe tr").hover( function () { $(this).addClass("hover") }, function () { $(this).removeClass("hover") } );

    $(".reset").click(function() {
        $(this.form).find(':input').each(function() {
            switch (this.type) {
            case 'password':
            case 'select-multiple':
            case 'select-one':
            case 'text':
            case 'textarea':
                $(this).val('');
                break;
            case 'checkbox':
            case 'radio':
                this.checked = false;
                break;
            case 'hidden':
                break;
            }
        });
        return false;
    });

    //colorbox
    jQuery(".op_box").colorbox({
        iframe:true,
        maxWidth:"90%",
        maxHeight:"90%",
		innerWidth:"90%",
		innerHeight:"90%",
		overlayClose: false,
        opacity: 0.7
    });
    jQuery(".op_box_s").colorbox({
        iframe:true,
        maxWidth:"40%",
        maxHeight:"40%",
		innerWidth:"40%",
		innerHeight:"40%",
		overlayClose: false,
        opacity: 0.7
    });
    jQuery(".op_box_h_s").colorbox({
        iframe:true,
        maxWidth:"90%",
        maxHeight:"80%",
		innerWidth:"90%",
		innerHeight:"80%",
		overlayClose: false,
        opacity: 0.7
    });
/*    var heightBox = 0;
   if( $(window).height() > 720){
        heightBox = '50%';
    }else if($(window).height() <= 720){
        heightBox = '80%';
    }*/
    jQuery(".op_box_m").colorbox({
        iframe: true,
        innerHeight: "60%",
        innerWidth: "60%",

		overlayClose: false,
        opacity: 0.7,
        onComplete: function () {
                $(this).colorbox.resize();
          }
    });

    jQuery(document).on('cbox_open',function(){
			jQuery('body').css("overflow","hidden");
		})
	jQuery(document).on('cbox_closed',function(){
			jQuery('body').css("overflow","visible");
	});

    //slideToggle
    jQuery('.btn_srch').on('click', function() {
            $('#skForm').slideToggle();
    });

  });//ready

})(jQuery);

var pageSeek = function (sk) {
  jQuery('#sk').val(sk);
  jQuery('#skForm').submit();
}
var rand_str = function (len) {
  var source = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM-';
  var result = '';
  for ( var i = 0; i < len; i++ ) {
    result += source.charAt( Math.floor( Math.random() * source.length ) );
  }
  return result;
}
var pageSort = function (key) {
  jQuery('#Sort').val(key);
  var $sortN = jQuery('#SortN').val();
  if(!$sortN) $sortN = "DESC";
  else{
    if($sortN == "DESC") $sortN = "ASC";
    else $sortN = "DESC";
  }
  jQuery("#SortN").val($sortN);
  jQuery('#skForm').submit();
}

var resetForm = function(e){
    var form = $(e).closest("form");
    var id = form.attr("id");
    $("#"+id+" select").each(function(index){
        if($(this).attr('id') === "s_lead_time_category"){
            $(this).html('<option value="">選択してください。</option>');
        }
        else{
            $(this).find('option:selected').removeAttr("selected");
        }
    });
    $("#"+id+" input[type='text']").each(function(index){
        $(this).removeAttr("value");
    });
}

/*var changeStatus = function(element, tmp_code){
    $.ajax({
        type: "post",
        data: {tmp_code: tmp_code},
        url: element.href,
        success: function(koukai){
            if(koukai == 0){
                $(element).html("非公開");
                $(element).removeClass("koukai-0");
                $(element).addClass("koukai-1");
            }
            else{
                $(element).html("公開");
                $(element).removeClass("koukai-1");
                $(element).addClass("koukai-0");
            }
        }
    })
}*/