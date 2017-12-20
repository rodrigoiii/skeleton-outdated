$(document).ready(function() {
	$.ajaxSetup({
		complete: function (jqXHR)
		{
			var new_token = jqXHR.getResponseHeader('X-CSRF-TOKEN');
			$('meta[name="_token"]').attr('content', new_token);

			if ($(':input[name="{{ csrf.name_key }}"]').length > 0 && $(':input[name="{{ csrf.value_key }}"]').length > 0)
			{
				var csrf = JSON.parse(new_token);
				for (var key in csrf)
				{
					$(':input[name="'+key+'"]').val(csrf[key]);
				}
			}
		}
	});
});

function postWithToken(data)
{
	var csrf = {};

	if ($('meta[name="_token"]').length > 0)
	{
		var token = JSON.parse($('meta[name="_token"]').attr('content'));
		for (var key in token)
		{
		    csrf[key] = token[key];
		}
	}

	return $.extend(csrf, data);
}