$(function() {

	$(document).on('change', '.menu-form #menuform-type', function() {
		var $this = $(this),
			type = parseInt($this.val()),
			$form = $this.closest('form'),
			$urlGroup = $form.find('.field-menuform-url'),
			$aliasGroup = $form.find('.field-menuform-alias');

		if (type === 1) {
			$urlGroup.removeClass('hidden');
		} else {
			$urlGroup.addClass('hidden');
		}

		if (type === 0 || type === 1) {
			$aliasGroup.addClass('hidden');
		} else {
			$aliasGroup.removeClass('hidden');
			refreshAliasList($aliasGroup, type);
		}
	});

	function refreshAliasList($aliasGroup, type)
	{
		var $select = $aliasGroup.find('select');

		$select.html('');
		
		$.get($aliasGroup.data('url'), {type: type}, function(data) {
			if (data['type'] === type) {
				$.each(data['items'], function(alias, value) {
					$('<option>').val(alias).text(value).appendTo($select);
				});
			}
		}, 'json');
	};

});