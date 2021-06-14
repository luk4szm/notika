jQuery(document).ready(function ($) {
    $(".row-link td:not(.unclickable)").click(function () {
        window.location = $(this).parent().data("href");
    });
});