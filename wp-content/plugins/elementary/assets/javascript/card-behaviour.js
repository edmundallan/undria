jQuery(document).ready(function() {

    jQuery('.loading-animation').show();

    console.log("doc ready for card_behaviour!");

    // jQuery('.hover-image-card .title').hide();
    //
    // jQuery(document).on('hover', '.hover-image-card', function() {
    //     jQuery('.hover-image-card .title').show();
    // });
    //
    // jQuery(document).on('mouseleave', '.hover-image-card', function() {
    //     jQuery('.hover-image-card .title').hide();
    // });

});

jQuery(window).on("load", function() {

    // jQuery('.lisa-base .sub-title span').css({
    //     float:'left',
    //     display: 'block',
    //     width: '100%',
    // });


    jQuery('.loading-animation').hide();
      jQuery('.page-content').velocity({
          opacity: 1
      }, {
          duration: 800,
          easing: "easeInSine"
      });


    var numElements = jQuery('.karla-card').length;



    jQuery('.lisa-base').mouseenter(function() {

        jQuery(this).find('.additional-content').slideDown(['200'],['spring']);

        jQuery(this).find('.image-overlay').velocity('slideDown', {
            duration: 200,
            easing: "linear"
        });
    });

    jQuery('.lisa-base').mouseleave(function() {

        jQuery(this).find('.additional-content').velocity('slideUp', {
            duration: 200,
            easing: "spring"
        });

        jQuery(this).find('.image-overlay').velocity('slideUp', {
            duration: 100,
            easing: "spring"
        });
    });

    /* shutter-card */

    jQuery('.shutter-card').mouseenter(function() {

        jQuery(this).find('.image-overlay').velocity({
            left: '-100%',
            boxShadowX: '10px',
            boxShadowY: '19px',
            boxShadowZ: '76px',
            boxShadowBlur: 20
        }, {
            duration: 500,
            easing: "swing"
        });

        jQuery(this).find('.buy-now').velocity({
            boxShadowX: '0',
            boxShadowY: '19px',
            boxShadowZ: '76px',
            boxShadowBlur: 20
        }, {
            duration: 1000,
            easing: "swing"
        });

    });


    jQuery('.shutter-card').mouseleave(function() {

        jQuery(this).find('.image-overlay').velocity({
            left: '0',
            boxShadowX: '0',
            boxShadowY: '1px',
            boxShadowZ: '3px',
            boxShadowBlur: 10
        }, {
            duration: 1000,
            easing: "swing"
        });

        jQuery(this).find('.buy-now').velocity({
            boxShadowX: '0',
            boxShadowY: '1px',
            boxShadowZ: '3px',
            boxShadowBlur: 10
        }, {
            duration: 500,
            easing: "swing"
        });
    });



    /* rio-card-card */

    jQuery('.rio-card').mouseenter(function() {

        jQuery(this).find('.additional-content').velocity({
            bottom: '30px',
            opacity: 0,
        }, {
            duration: 500,
            easing: "swing"
        });

        jQuery(this).find('.featured-img').velocity({
            scale: '1.2',
            opacity: 1,
        }, {
            duration: 500,
            easing: "swing"
        });


    });


    jQuery('.rio-card').mouseleave(function() {

        jQuery(this).find('.additional-content').velocity({
            bottom: '0',
            opacity: 1,
        }, {
            duration: 500,
            easing: "swing"
        });

        jQuery(this).find('.featured-img').velocity({
            scale: '1',
            opacity: 0.8,
        }, {
            duration: 500,
            easing: "swing"
        });

    });

    /* rio-baixa-card */

    jQuery('.rio-baixa-card').mouseenter(function() {

        jQuery(this).find('.additional-content').velocity({
            bottom: '0',
            opacity: 1,
        }, {
            duration: 500,
            easing: "swing"
        });

        jQuery(this).find('.featured-img').velocity({
            scale: '1.2',
            opacity: 1,
        }, {
            duration: 500,
            easing: "swing"
        });


    });


    jQuery('.rio-baixa-card').mouseleave(function() {

        jQuery(this).find('.additional-content').velocity({
            bottom: '30px',
            opacity: 0,
        }, {
            duration: 500,
            easing: "swing"
        });

        jQuery(this).find('.featured-img').velocity({
            scale: '1',
            opacity: 0.8,
        }, {
            duration: 500,
            easing: "swing"
        });

    });


    /* LAUDE CARD */

    jQuery('.laude-card').mouseenter(function() {

        jQuery(this).find('.img-wrapper').velocity({
            marginTop: '-100%',
        }, {
            duration: 400,
            easing: "easeInSine"
        });
    });


    jQuery('.laude-card').mouseleave(function() {

        jQuery(this).find('.img-wrapper').velocity({
            marginTop: '0',
        }, {
            duration: 400,
            easing: "easeOutSine"
        });
    });


    /* LAUDE PARK CARD */

    jQuery('.laude-park-card').mouseenter(function() {


        jQuery(this).find('.img-wrapper').velocity({
            marginTop: '-30px',
        }, {
            duration: 200,
            easing: "swing"
        });


        jQuery(this).find('.content-wrapper').velocity('slideDown', {
            duration: 200,
            easing: "swing"
        });


    });

    jQuery('.laude-park-card').mouseleave(function() {

        jQuery(this).find('.img-wrapper').velocity({
            marginTop: '0',
        }, {
            duration: 200,
            easing: "swing"
        });

        jQuery(this).find('.content-wrapper').velocity('slideUp', {
            duration: 200,
            easing: "swing"
        });


    });



    /* NEOCARD */

    jQuery('.neo-card').mouseenter(function() {


        jQuery(this).find('.img-wrapper').velocity({
            top: '-33%',
        }, {
            duration: 500,
            easing: "easeInSine"
        });

        jQuery(this).find('.content-wrapper').velocity({
            top: '-10%',
            scale: '1.2',
        }, {
            duration: 500,
            easing: "easeInSine"
        });



    });

    jQuery('.neo-card').mouseleave(function() {

        jQuery(this).find('.img-wrapper').velocity({
            top: '0',
        }, {
            duration: 500,
            easing: "easeOutSine"
        });

        jQuery(this).find('.content-wrapper').velocity({
            top: '0',
            scale: '1',
        }, {
            duration: 500,
            easing: "easeOutSine"
        });

    });


    /* KARLA CARD */

    jQuery('.karla-card').mouseenter(function() {
        jQuery(this).find('.overlay-wrapper').velocity({
            opacity: 1
        }, {
            display: "block"
        });
    });

    jQuery('.karla-card').mouseleave(function() {
        jQuery(this).find('.overlay-wrapper').velocity({
            opacity: 0
        }, {
            display: "none"
        });
    });


    /* AIRY CARD */
    jQuery('.airy-card').mouseenter(function() {
        jQuery(this).find('.overlay-wrapper').velocity({
            opacity: 1
        }, {
            display: "block"
        });

        jQuery(this).find('.featured-img').velocity({
            scale: '1.1',
        }, {
            duration: 1500,
            easing: "swing"
        });

    });


  //   jQuery('.airy-card').find('img').each(function() {
  //     var imgClass = (this.width / this.height > 1) ? 'wide' : 'tall';
  //     jQuery(this).addClass(imgClass);
  // });


    jQuery('.airy-card').mouseleave(function() {

        jQuery(this).find('.overlay-wrapper').velocity({
            opacity: 0
        }, {
            display: "none"
        });

        jQuery(this).find('.featured-img').velocity({
            scale: '1',
        }, {
            duration: 700,
            easing: "swing"
        });

    });

    // END
});



// jQuery(window).on("load", function() {
//
//     jQuery('.loading-animation').hide();
//     jQuery('.page-content').velocity({
//         opacity: 1
//     }, {
//         duration: 800,
//         easing: "easeInSine"
//     });
//
// });
