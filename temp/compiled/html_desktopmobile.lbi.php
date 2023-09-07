<script type="text/javascript">
  $(function() {
  function slideMenu() {
    var activeState = $("#menu-container .menu-list").hasClass("active");
    $("#menu-container .menu-list").animate({left: activeState ? "0%" : "-100%"}, 400);
  }
  $("#menu-wrapper").click(function(event) {
    event.stopPropagation();
    $("#hamburger-menu").toggleClass("open");
    $("#menu-container .menu-list").toggleClass("active");
    slideMenu();

    $("body").toggleClass("overflow-hidden");
  });

  $(".menu-list").find(".accordion-toggle").click(function() {
    $(this).next().toggleClass("open").slideToggle("fast");
    $(this).toggleClass("active-tab").find(".menu-link").toggleClass("active");

    $(".menu-list .accordion-content").not($(this).next()).slideUp("fast").removeClass("open");
    $(".menu-list .accordion-toggle").not(jQuery(this)).removeClass("active-tab").find(".menu-link").removeClass("active");
  });
    $("li.show_cat3").each(function() {
    if ($(this).find('.sub_cat_2').length > 0) {
      $(this).addClass('has-sub');  // Thêm class 'has-sub' nếu thẻ 'li' có thẻ 'ul' con với class 'sub_cat_2'
    }
  });

  $("li.show_cat3").click(function(e) {
    if (!$(e.target).is('a')) {
      $(this).find('.sub_cat_2').slideToggle("fast");
      $(this).toggleClass('open');
    }
  });
}); 
</script>