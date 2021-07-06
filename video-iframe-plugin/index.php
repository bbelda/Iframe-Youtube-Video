<?php
/**
 * Plugin Name: Iframe Youtube Plugin
 * Plugin URI: none
 * Description: This plugin use for the dynamic videos come from youtube.
 * Version: 1.0
 * Author: Bernard Belda Jr.
 * Author URI: None
 */

function stylingVideo() {
  wp_enqueue_style( 'iframe-youtube-video', plugins_url( '/css/style.css', __FILE__ ) );
  wp_enqueue_script('jquery-2','https://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js');
}
add_action( 'wp_print_styles', 'stylingVideo' );

add_shortcode( 'iframe_youtube_shortcode', 'videoIframe' );

 function videoIframe($atts) {
  $atts = shortcode_atts(
    array(
        'id' => '',
        'img-src' =>  '',
    ), $atts);

    global $id_video_youtube;

    $id_video_youtube = $atts['id'];

    ob_start();
    ?>
    <div class="process-video-container play-button-trigger">
      <div class="video-container">
        <div id="player_<?php echo $atts['id']; ?>"></div>
      </div>
      <div class="video-preview" id="video-preview_<?php echo $atts['id']; ?>">
      <div class="video-thumbnail">
        <img src="<?php echo $atts['img-src']; ?>" alt="">
      </div>
        <div class="player-icon">
          <span class="icon-play"></span>
        </div>	
      </div>
    </div>
    <?php
    return ob_get_clean();
 }


 add_action('wp_footer', 'addScriptToFooter');

 function addScriptToFooter() {
  global $id_video_youtube;
  ?>
  <script>
	var tag = document.createElement('script');
	tag.src = "https://www.youtube.com/iframe_api";
	var firstScriptTag = document.getElementsByTagName('script')[0];
	firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

	if(jQuery("#player_<?php echo $id_video_youtube; ?>").length) {
		var player;
		var youtubeVideoID = "<?php echo $id_video_youtube; ?>";
		
		// console.log("youtubeVideoID: " + youtubeVideoID);
		// console.log("id count: " + jQuery('#player').length);
		jQuery(window).load(function(){
			console.log("YouTube API Ready");

			player = new YT.Player('player_<?php echo $id_video_youtube; ?>', {
				height: '504',
				width: '100%', 
				playerVars: {
					frameborder: 0,
				},
				videoId: youtubeVideoID,
				events: {
					onReady: onPlayerReady,
					onStateChange: onPlayerStateChange,
					onError: onPlayerError
				}
			}); 

			ytLoaded = true;
			// console.log("ytLoaded: " + ytLoaded);

			function onPlayerReady(event) {
				var clickElem = event.target;
				// console.log(event);
				// console.log(player);
				jQuery("#video-preview_<?php echo $id_video_youtube; ?>").on('click',function(e) {
					e.preventDefault();
					jQuery(".process-video-container").addClass("playing");
					clickElem.playVideo();
					// clickElem.loadVideoById({videoId: youtubeVideoID}).playVideo();
				})
			}
			// when video ends
			function onPlayerStateChange(event) {
				// console.log(event);
				if (event.data === 1) {
					console.log('playing');
				}
				if(event.data === 3 ) {            
					// console.log('buffer');
					jQuery(".process-video-container").addClass("playing");
				}
				if(event.data === 0) {            
					// console.log('done');
					jQuery(".process-video-container").removeClass("playing");
				}
			}
			function onPlayerError(event) {
				// console.log(event);
			}
		})
	}
</script>
  <?php
 }