 
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
    $('#cart').mouseenter(function(){
        loadCart();
    });
	globalInt();
	if ($('#page_goods').length){
        goodsInt();
    }

    $("#searchForm .search-text").focus(function(){
        console.log('hi');
        $("#searchForm").addClass("search-form-focus");
    }).blur(function(){
        if($(this).val().length==0){
            $("#searchForm").removeClass("search-form-focus");
        }
    });

});

$(window).scroll(function(){
        if( $(window).scrollTop() == 0 ) {
            $('#back-top').stop(false,true).fadeOut(600);
        }else{
            $('#back-top').stop(false,true).fadeIn(600);
        }
    });
$('#back-top').click(function(){
    $('body,html').animate({scrollTop:0},300);
    return false;
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

function globalInt() {
	$('.tip').tipsy({gravity:'s',fade:true,html:true});
}
function goodsInt() {
    $('#purchase_form').ChangePriceSiy();
	if ($('.properties').length) {
		$('.properties').Formiy();
		$('.properties dl').tipsy({gravity: 'e',fade: true,html:true});
		$('.properties label').tipsy({gravity: 's',fade: true,html:true});
	}
}
function showmorefootlink(n){$(n).parents(".colfoot").find(".hidden").show();$(n).parent().remove()};

function fixcategory() {var $cache = $('header .hnav'); if ($(window).scrollTop() > 150) $cache.css({'position': 'fixed','z-index': '999','top': '0'}); else $cache.css({'position': 'relative','top': 'auto'}); } $(window).scroll(fixcategory); fixcategory();