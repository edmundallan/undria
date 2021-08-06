(function($) {

    jQuery(document).on( 'click', '.elementary-license-notice .notice-dismiss', function() {

        var data = {
            "action": "elementary_dismiss_license_notice"
        };

        jQuery.post(ajaxurl, data);

    });

})(jQuery);
