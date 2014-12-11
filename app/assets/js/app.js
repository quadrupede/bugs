$(function(){

   if($('.global-notice').html().length > 0){

   	$('.global-notice').slideDown();

   	setTimeout(function(){
   		$('.global-notice').slideUp();
   	}, 15000);

   	$('.global-notice').live('click', function(){
   		$('.global-notice').slideUp();
   	});
   }
	moment.locale(window.navigator.language);
	$('.moment').each(function()
	{
		var html = $(this).html();
		var time = parseInt(html, 10)*1000;
		var thisMoment = moment(isNaN(time) ? html : time);
		$(this).html(thisMoment.fromNow());
	});

});

var saving = false;

function saving_toggle(){
	if(saving){
		$('.global-saving').hide();
		saving = false;
	}else{
		$('.global-saving').show();
		saving = true;
	}
}
