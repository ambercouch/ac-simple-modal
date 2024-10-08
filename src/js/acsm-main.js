console.log('acsm testing may 2024 scope');
let settings = {
    debug : false,
    expires : 1
}

if (settings.debug == true){
    Cookies.set('acsm_closed', 0, { expires: 7 });
    Cookies.set('_acsm_intented', 0, { expires: 7 });

}

function setCookie(name = 'acsm_closed', val = '1'){
    //console.log('cookie SET');
    Cookies.set(name, val, { expires: 7 })
}



jQuery(function($) {
    $(function() {
        $(document).ready(function () {

            $.each($('[data-modal-opener]'), function (i) {

                var openId = $(this).attr('data-modal-opener');


                $(this).modaal({
                    content_source: '[data-modal="'+openId+'"]'
                });

            })

            // Event listener for mouse leaving the viewport
            $('[data-modal-onexit]').each(function(i) {
                console.log('on exit i : ' + i);
                let $modal = $(this);
                let modalSettings = {
                    content_source: $modal,
                    overlay_opacity: 0.4,
                    hide_close: false,
                    custom_class: 'act-modal-exit-' + i // Set custom class to uniquely identify modals

                };
                $modal.modaal(modalSettings);
                document.addEventListener('mouseleave', function (e) {
                    if (e.clientY <= 0 && Cookies.get('_acsm_intented') != 1) {
                        console.log('leaving !');
                        (typeof settings !== 'undefined' && settings.debug != true) ? Cookies.set('_acsm_intented', '1', { expires: 30 }) : console.log('cookie test');
                        $modal.modaal('open');
                    }
                });
            });


            $('[data-modal-onload]').each(function(i) {
                console.log('on load i : ' + i);

                let $modal = $(this);


                let modalSettings = {
                    content_source: $modal,
                    overlay_opacity: 0.4,
                    after_close: (typeof settings !== 'undefined' && settings.debug != true) ? setCookie : function() { return false; },
                    hide_close: false,
                    after_open: function(){
                        console.log('on open')
                        let modalID = $(this).attr('data-modaal-scope');
                        console.log('data-modaal-scope');
                        console.log(modalID);
                    },
                    custom_class: 'act-modal-' + i // Set custom class to uniquely identify modals

                };

                $modal.modaal(modalSettings);

                var acsm_closed = Cookies.get('acsm_closed');

                // Open the onload modal if not closed
                if (acsm_closed != 1) {
                    $modal.modaal('open');
                }

                // Bind the close event to the modal instance
                $(document).on('click', '.act-modal-' +i+ ' .modaal-close', function () {
                    $modal.modaal('close');
                })


            });


        })
    });
});

