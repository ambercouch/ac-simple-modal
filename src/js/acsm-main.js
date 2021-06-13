
$(document).ready(function () {

    $('[data-modal]').modaal({
        content_source: '[data-modal]'
    });
    $('[data-modal]').modaal('open');

    $('[data-modal-close]').on('click', function () {
        $('[data-modal]').modaal('close');
    })

})

