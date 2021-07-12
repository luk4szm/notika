$(window).scroll(function () {
    if ($(this).scrollTop() > 0) {
        $('.navbar').addClass("navbar-shadow");
    } else {
        $('.navbar').removeClass("navbar-shadow");
    }
});