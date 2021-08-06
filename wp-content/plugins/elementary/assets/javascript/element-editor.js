(function($) {

    $('#customize_element').click(function(){
        postTitle = $.trim($('#title').val());
        if (postTitle == null || postTitle == "") {
            $('#title').css({"border":"solid 1px red"});
            return false;
        }
        else {
            encodedTitle = encodeURIComponent(postTitle);
            url = $(this).attr('href');
            $(this).attr('href', url+ '&title='+encodedTitle);
        }
    });

})(jQuery);
