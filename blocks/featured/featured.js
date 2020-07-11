function onYouTubeIframeAPIReady() {
    if (jQuery('body').hasClass('jma-desktop') && jQuery(window).width() > jQuery(window).height())
        jQuery('.jma-yt-video').each(function() {
            $this = jQuery(this);
            vid_id = $this.data('yt_id');
            $player = new YT.Player('video' + vid_id, {
                videoId: vid_id,
                //height: $this.height(),
                //width: $this.width(),
                playerVars: {
                    rel: 0,
                    origin: document.domain,
                    enablejsapi: 1,
                    controls: 0,
                    showinfo: 0,
                    loop: 1,
                    disablekb: 1,
                    mute: 1,
                    modestbranding: 1,
                    cc_load_policy: 0,
                    iv_load_policy: 3,
                    autohide: 0,
                },
                events: {
                    // call this function when player is ready to use
                    'onReady': jmayt_ghb_onPlayerReady,
                    'onStateChange': jmayt_onPlayerStateChange
                }
            });
        });
}

function jmayt_ghb_onPlayerReady(event) {
    $iframe = event.target.f;
    $jma_yt_video = jQuery($iframe).parents('.jma-yt-video');
    available_ratio = $jma_yt_video.height() / $jma_yt_video.width();
    if (($jma_yt_video.height() + 120) / $jma_yt_video.width() < 0.5625) {
        //wide hole
        jQuery($iframe).css({
            'height': ($jma_yt_video.width() * (1 / available_ratio)) + 'px'
        });
    } else {
        jQuery($iframe).css({
            'height': ($jma_yt_video.height() + 120) + 'px',
            'width': (($jma_yt_video.height() + 120) / 0.5625) + 'px',
            'max-width': 'unset'
        });
    }
    $jma_yt_video.find('img').delay(1000).animate({
        opacity: 0
    }, 500);
    event.target.playVideo();
}

function jmayt_onPlayerStateChange(event) {
    event.target.playVideo();
}