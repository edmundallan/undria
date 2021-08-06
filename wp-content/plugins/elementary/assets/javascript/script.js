(function($) {

    var windowHeight = $(window).height();
    var viewControllerHeight = 60;
    var wpadminbarHeight = 32;
    var previewHeight = windowHeight - viewControllerHeight;
    var editorHeight = previewHeight;

    $(".editor-tools").height(previewHeight);
    var editorToolsTop = parseInt(wpadminbarHeight) + parseInt(viewControllerHeight);
    $(".editor-tools").css("top", editorToolsTop);

    var editor_nav_height = 50;
    var editor_body_height = editorHeight - editor_nav_height;

    $(".editor-tools .editor-body").height(editor_body_height);

})(jQuery);
