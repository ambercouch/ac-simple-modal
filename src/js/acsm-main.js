// Universal settings
let settings = {
    debug: false,
    expires: 1
};

// Cookie helpers
function setCookie(name = 'acsm_closed', val = '1') {
    Cookies.set(name, val, { expires: settings.expires });
}

// Reset cookies for debug mode
if (settings.debug) {
    Cookies.set('acsm_closed', 0, { expires: 7 });
    Cookies.set('_acsm_intented', 0, { expires: 7 });
}

// Init modals
jQuery(function($) {
    $(document).ready(function () {

        console.log('tib test');
        // Openers (inline / video)
        $('[data-modal-opener]').each(function () {
            const openId = $(this).attr('data-modal-opener');
            const modalType = $(this).data('modal-type') || 'inline';
            const $modal = $('[data-modal="' + openId + '"]');
            const contentSrc = modalType === 'video' ? $modal.find('iframe').attr('data-src') : $modal;


            console.log('contentSrc');
            console.log(contentSrc);

            console.log('openId');
            console.log(openId);


            $(this).modaal({
                type: modalType,
                content_source: contentSrc
            });
        });

        // ON EXIT modals
        $('[data-modal-onexit]').each(function(i) {
            const $modal = $(this);
            const modalClass = 'acsm-exit-' + i;

            $modal.modaal({
                content_source: $modal,
                custom_class: modalClass,
                overlay_opacity: 0.4
            });

            document.addEventListener('mouseleave', function(e) {
                if (e.clientY <= 0 && Cookies.get('_acsm_intented') != '1') {
                    settings.debug ? console.log('Exit detected') : Cookies.set('_acsm_intented', '1', { expires: 30 });
                    $modal.modaal('open');
                }
            });
        });

        // ON LOAD modals
        $('[data-modal-onload]').each(function(i) {
            const $modal = $(this);
            const modalClass = 'acsm-load-' + i;

            $modal.modaal({
                content_source: $modal,
                custom_class: modalClass,
                overlay_opacity: 0.4,
                after_open: function() {
                    if (settings.debug) {
                        console.log('Opened modal:', $(this).attr('data-modaal-scope'));
                    }
                },
                after_close: settings.debug ? function() {} : setCookie
            });

            if (Cookies.get('acsm_closed') != '1') {
                $modal.modaal('open');
            }

            $(document).on('click', '.' + modalClass + ' .modaal-close', function () {
                $modal.modaal('close');
            });
        });
    });
});
