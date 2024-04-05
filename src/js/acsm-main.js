let settings = {
    debug : true,
    expires : 1
}

if (settings.debug == true){
    Cookies.set('acsm_closed', 0, { expires: 7 });
    Cookies.set('_acsm_intented', 0, { expires: 7 });

}

function setCookie(name = 'acsm_closed', val = '1'){
    //console.log('cookie SET');
    Cookies.set(name, val, { expires: 0.5 })
}



jQuery(function($) {
$(function() {
    $(document).ready(function () {

        $.each($('[data-modal-opener]'), function (i) {

            var openId = $(this).attr('data-modal-opener');
            var inlineModal = $('[data-modal="' + openId + '"]');

            // Check for the 'data-modal-type' attribute and use it if it exists
            var contentType = $(this).data('modal-type') || 'inline';

            console.log('openId : ' + openId);
            console.log('contentType : ' + contentType);

            var contentSource = (contentType == 'video')? inlineModal.find('iframe').attr('data-src') : inlineModal ;

            $(this).modaal({
                type: contentType,
                content_source:  contentSource
            });

        })


        let modalSettings = {
            content_source: '[data-modal]',
            overlay_opacity: 0.4
        };



        modalSettings.after_close = ( settings.debug != true ) ? setCookie : false;


        $('[data-modal-onload]').modaal(modalSettings);
        $('[data-modal-onexit]').modaal(modalSettings);

        var acsm_closed = Cookies.get('acsm_closed')

        // Open the onload modal if not closed
        if(acsm_closed != 1 ){
            $('[data-modal-onload]').modaal('open');
        }

// Event listener for mouse leaving the viewport
        document.addEventListener('mouseleave', function(e) {
            if (e.clientY <= 0 && Cookies.get('_acsm_intented') != 1) {
                Cookies.set('_acsm_intented', '1')
                $('[data-modal-onexit]').modaal('open');
            }
        });





        $('[data-modal-close]').on('click', function () {
            $('[data-modal]').modaal('close');
        })

    })
});
});

