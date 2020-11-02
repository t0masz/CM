$(document).ready(function(){
	// EasyJsConfirm
	$('[data-confirm]').click(function(){
		return confirm($(this).data().confirm);
	});
});