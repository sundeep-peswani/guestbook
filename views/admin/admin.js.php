<?php if (!is_admin()) return; ?>
<script src="http://www.google.com/jsapi?key=<?=option('setting_google_api_key')?>" type="text/javascript"></script>
<script type="text/javascript">
	google.load("jquery", "1");
  google.load("jqueryui", "1");
	
	function initialize() {
		$('a.approve').click(function(event) {
			event.preventDefault();
			if ($(this).parent().attr('rel') == 'approved') { return; }
			
			target = $(this);
			id = target.attr('href').match(/(\d+)/)[1];
			$.getJSON('/admin/'+id+'/approve', function(data) {
				if (data.success) {
					target.parent().attr('rel', 'approved');
					$('dt.entry-'+id).removeClass('disapproved unapproved').addClass('approved');
					$('dd.entry-'+id).removeClass('disapproved unapproved').addClass('approved');
				} else {
					author = $('dt.entry-'+id+' .author').html();
					date = $('dt.entry-'+id+' .date').html();
					$('.error').html('Could not approve entry by '+author+' on '+date);
				}
			});
		});
		$('a.disapprove').click(function(event) {
			event.preventDefault();
			if ($(this).parent().attr('rel') == 'disapproved') { return; }

			target = $(this);
			id = target.attr('href').match(/(\d+)/)[1];
			$.getJSON('/admin/'+id+'/disapprove', function(data) {
				if (data.success) {
					target.parent().attr('rel', 'disapproved');
					$('dt.entry-'+id).removeClass('approved unapproved').addClass('disapproved');
					$('dd.entry-'+id).removeClass('approved unapproved').addClass('disapproved');
				} else {
					author = $('dt.entry-'+id+' .author').html();
					date = $('dt.entry-'+id+' .date').html();
					$('.error').html('Could not disapprove entry by '+author+' on '+date);
				}
			});
		});
		$('a.delete').click(function(event) {
			event.preventDefault();
			
			target = $(this);
			id = target.attr('href').match(/(\d+)/)[1];
			$.getJSON('/admin/'+id+'/delete', function(data) {
				if (data.success) {
					$('dt.entry-'+id).hide();
					$('dd.entry-'+id).hide();
					target.parent().hide();
				} else {
					author = $('dt.entry-'+id+' .author').html();
					date = $('dt.entry-'+id+' .date').html();
					$('.error').html('Could not delete entry by '+author+' on '+date);
				}
			});
		});
	}
	google.setOnLoadCallback(initialize);
</script>