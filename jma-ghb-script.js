jQuery(document).ready(function($) {
    var featured_display_ratio = 0;
    if ($('.jma-ghb-featured-display').length) {
        var $featured_display = $('.jma-ghb-featured-display');
        featured_display_ratio = $featured_display.height() / $featured_display.width();
    }

    function resize_feature() {
        if (featured_display_ratio) {
            $target = $featured_display.closest('.inner-visual');
            target_ratio = $target.height() / $target.width();
            if (target_ratio < featured_display_ratio) {
                //target is taller than image use 100% width and left
                //top and bottom overflow and adjust dots
                $featured_display.css({
                    'width': '100%',
                    'height': ($target.width() * featured_display_ratio) + 'px'
                });
                bottom = (((($target.width() * featured_display_ratio) - $target.height()) / 2) + 20);
                $featured_display.find('.soliloquy-pager').css('bottom', bottom + 'px');
                $featured_display.find('.soliloquy-controls-direction').css({
                    'width': '100%'
                });
            } else {
                //target is wider than image use 100% height and left
                //sides overflow and adjust arrows
                $featured_display.css({
                    'height': $target.height() + 'px',
                    'width': $target.height() * (1 / featured_display_ratio) + 'px'
                });
                $featured_display.find('.soliloquy-controls-direction').css({
                    'width': $target.width() + 'px'
                });
            }
        }
    }




    $window = $(window);
    $window.load(function() {
        resize_feature();
        //menuadjust();
    });
    /*

            $window.scroll(function() {
                sticktothetop();
            });*/

    $window.resize(function() {
        resize_feature();
    });

});