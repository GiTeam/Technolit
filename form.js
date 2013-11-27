// JavaScript source code

$(document).ready(function () {
        
        $('#minus').hide();

        var count = 1;
        var $form = $(".form-container");
        //alert(count);

        $('#minus').click(function (removeGoods) {

            if (count > 2) {
			
				count = count - 1;
                $('ol#itemBox > li:last-child').remove();
                
            }
            else {
				count = count - 1;
				$('ol#itemBox > li:last-child').remove();
                $('#minus').hide();
            }

        });

        $('#plus').click(function (event) {

			count=count+1;
            $('#itemBox').append('<li><input class="form-field" type="text" name="goods'+count+'" /></li>');
            $('#minus').show();
            $('#itemBox').css(goods);

        });

        $('.submit-butt').click(function (evente) {
            var $item = $(this).parent().parent();
            var nameItem = $item.find(".name").text();
			
			for(var i = 1; i <= count+1; i++){
				if ( i > count ){
				
					$('#itemBox').append('<li><input class="form-field" type="text" name="goods'+i+'" /></li>');
					$form.find('input[name=goods'+i+']').val( nameItem );
					$('#minus').show();
					count=count+1;
					return false;
					
				}
				if( $form.find('input[name=goods'+i+']').val() == '' ) {
				
					$form.find('input[name=goods'+i+']').val( nameItem );
					return false;	
				}
			}
        });
		
});

