
$(document).ready(function() {
	$('body').append('<div id="loading_box">' + lang.process_request + '</div>');
	$('#loading_box').ajaxStart(function(){
		var loadingbox = $(this);
		var left = -(loadingbox.outerWidth() / 2);
		loadingbox.css({'marginRight': left + 'px'});
		loadingbox.delay(3000).fadeIn(400);
	});
	$('#loading_box').ajaxSuccess(function(){
		$(this).stop().stop().fadeOut(400);
	});

	$('.tip').tipsy({gravity:'s',fade:true,html:true});

	if ($('#page_goods').length){
        $('#purchase_form').ChangePriceSiy();
        if ($('.properties').length) {
            $('.properties').Formiy();
            $('.properties dl').tipsy({gravity: 'e',fade: true,html:true});
            $('.properties label').tipsy({gravity: 's',fade: true,html:true});
        }
    }

    $('.gototop').click(function(){
        $('body,html').animate({scrollTop:0},300);
    });
    $('#_menu').click(function(){
        $('#_subnav, div.over').toggleClass('show');
        $('#_menu').toggleClass('actmenu');
    });
    $('#_infoother').click(function(){
        $('#_subother').toggleClass('show');
    });


});

$.fn.delayKeyup = function(callback, ms){
    var timer = 0;
    var el = $(this);
    $(this).keyup(function(){
    clearTimeout (timer);
    timer = setTimeout(function(){
        callback(el)
        }, ms);
    });
    return $(this);
};

$('#search-keyword').delayKeyup(function(el){
    var keywords = el.val();
    var show = $('#search-site .search-suggest');
    if(keywords.length >2){
        $.post(
            'ajax/search_suggest.php',
            {keywords: keywords},
            function(response){
                var res = $.evalJSON(response);
                show.show();
                show.html(res.content);
            },
            'text'
        );
    }else{
        show.hide();
    }
},500);