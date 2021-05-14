var spinner = $('<div class="spinner"></div><div class="overlay"></div>');
$(document).ajaxStart(function () {
    $('#spinner').append(spinner);
}).ajaxStop(function () {
    $('#spinner').empty();
});
