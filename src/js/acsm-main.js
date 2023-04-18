let settings = {
    debug : false,
    expires : 1
}

if (settings.debug == true){
    Cookies.set('acsm_closed', 0, { expires: 7 })
}

function setCookie(name = 'acsm_closed', val = '1'){
    console.log('cookie SET');
    Cookies.set(name, val, { expires: 0.5 })
}





jQuery(function($) {
$(function() {
    $(document).ready(function () {


console.log('expires');
console.log(settings.expires);
        $('[data-modal-opener]').modaal({
            content_source: '[data-modal]'
        });

        let modalSettings = {
            content_source: '[data-modal]',
            overlay_opacity: 0.4
        };



        modalSettings.after_close = ( settings.debug != true ) ? setCookie : false;

        $('[data-modal-onload]').modaal(modalSettings);

        var acsm_closed = Cookies.get('acsm_closed')

        if(acsm_closed != 1 ){
            $('[data-modal-onload]').modaal('open');
        }


        $('[data-modal-close]').on('click', function () {
            $('[data-modal]').modaal('close');
        })

    })
});
});

