<?php
//Process the plugin options and return html code to display icons and links
function socialConnect_outputFunction() {
	$sc_twitter = get_option('sc_twitter');
	$sc_facebook = get_option('sc_facebook');
	$sc_googleplus = get_option('sc_googleplus');
	$sc_youtube = get_option('sc_youtube');
	$sc_tumblr = get_option('sc_tumblr');
	$sc_linkedin = get_option('sc_linkedin');
	$sc_rss = get_option('sc_rss');
	$sc_imgPath = plugins_url().'/social-connect-widget/img/elegant-themes/';
	$sc_imgSize = "width=40px";

	if ($sc_twitter) {
		$sc_twitter_output = '<a target="_blank" href="http://twitter.com/'.$sc_twitter.'" id="followTwitter"><img src="'.$sc_imgPath.'twitter.png" class="sc-icons" alt="Twitter" '.$sc_imgSize.'/></a>';
	}
	if ($sc_facebook) {
		$sc_facebook_output = '<a target="_blank" href="http://www.facebook.com/'.$sc_facebook.'" id="followFacebook"><img src="'.$sc_imgPath.'facebook.png" class="sc-icons" alt="Facebook" '.$sc_imgSize.'/></a>';
	}
	if ($sc_googleplus) {
		$sc_googleplus_output = '<a target="_blank" href="http://'.$sc_googleplus.'" id="followGooglePlus"><img src="'.$sc_imgPath.'google.png" class="sc-icons" alt="Google+" '.$sc_imgSize.'/></a>';
	}
	if ($sc_youtube) {
		$sc_youtube_output = '<a target="_blank" href="http://www.youtube.com/'.$sc_youtube.'" id="followYouTube"><img src="'.$sc_imgPath.'youtube.png" class="sc-icons" alt="YouTube" '.$sc_imgSize.'/></a>';
	}
	if ($sc_tumblr) {
		$sc_tumblr_output = '<a target="_blank" href="http://'.$sc_tumblr.'.tumblr.com" id="followTumblr"><img src="'.$sc_imgPath.'tumblr.png" class="sc-icons" alt="Tumblr" '.$sc_imgSize.'/></a>';
	}

	if ($sc_linkedin) {
		$sc_linkedin_output = '<a target="_blank" href="http://'.$sc_linkedin.'" id="followLinkedIn"><img src="'.$sc_imgPath.'linkedin.png" class="sc-icons" alt="LinkedIn" '.$sc_imgSize.'/></a>';
	}

	if ($sc_rss) {
		$sc_rss_output = '<a target="_blank" href="http://'.$sc_rss.'" id="subscribeRSS"><img src="'.$sc_imgPath.'rss.png" class="sc-icons" alt="RSS" '.$sc_imgSize.'/></a>';
	}
	$socialConnect_output = $sc_twitter_output . $sc_facebook_output . $sc_googleplus_output . $sc_youtube_output . $sc_tumblr_output . $sc_linkedin_output . $sc_rss_output;
	return $socialConnect_output;
}
?>