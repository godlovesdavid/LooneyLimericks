$(document).ready(function()
{
	$('.rating_stars').hover
	(
		// mouseover
		function()
		{
			$(this).prevAll().andSelf().addClass('rating_over');
		},
	
		// mouseout
		function()
		{
			$(this).prevAll().andSelf().removeClass('rating_over');
		}
	);
	
	// AJAX request to controller to handle a new rate
	$('.rating_stars').bind('click', function()
	{
		var id = $(this).parent().attr("id");
		var num = $(this).attr("class");
		var poststr = "id=" + id + "&stars=" + num;
		$.ajax(
		{
			url : "index.php?main&action=rate",
			cache : 0,
			data : poststr,
			success : function(result)
			{
				document.getElementById(id).innerHTML = result;
			}
		});
	});
});
