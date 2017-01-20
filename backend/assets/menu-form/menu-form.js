$(function() {

	$(document).on('change', '.menu-form #itemform-type', function() {
		var $this = $(this),
			type = parseInt($this.val()),
			$form = $this.closest('form'),
			$nameGroup = $form.find('.field-itemform-name'),
			$urlGroup = $form.find('.field-itemform-url'),
			$aliasGroup = $form.find('.field-itemform-alias');

		if ($form.data('typesWithName').indexOf(type) != -1) {
			$nameGroup.removeClass('hidden');
		} else {
			$nameGroup.addClass('hidden');
		}

		if ($form.data('typesWithUrl').indexOf(type) != -1) {
			$urlGroup.removeClass('hidden');
		} else {
			$urlGroup.addClass('hidden');
		}

		if ($form.data('typesWithAlias').indexOf(type) != -1) {
			$aliasGroup.removeClass('hidden');
			refreshAliasList($aliasGroup, type);
		} else {
			$aliasGroup.addClass('hidden');
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
