$(document).ready(
function() 
{

	$('.minus').click( 
	   function del()
	   {
		  var $id = $(this).parent().parent().attr('id');
		  console.log($id);
		  
		  $.post(
			 "cart/change_cart.php",
			 {
				id: $id, 
				action: 'del'
			 },
			 function (data)
			 {
				delFromCart(data.counter);
			 },
			 "json"
		  );
	   }
	);

	$('.add-button').click(
	   function add()
	   {
			var $id = $(this).parent().parent().attr('id');
			console.log($id);
		  
			$.post(
				"cart/change_cart.php",
				{
					id: $id,
					action: 'add'
				},
				function (data)
				{
					addToCart(data.counter, data.name);
				},
				"json"
			);
	   }
	);  
	  


	function delFromCart(counter)
	{
	   console.log(counter);
	}

	function addToCart(counter, name)
	{
		$('#itemBox').append('<li><span>'+name+'</span></li>');
	   console.log(counter+" "+name);
	}
	
}

);