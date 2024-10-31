<?php
include_once("config.php");


function slr_hide_admin_bar_from_front_end(){
  return false;
}
add_filter( 'show_admin_bar', 'slr_hide_admin_bar_from_front_end' );


wp_register_style( 'slr_plugin_style', plugin_dir_url( __FILE__ ).'css/style.css' );
wp_enqueue_style( 'slr_plugin_style' );

wp_register_style( 'slr_plugin_style1', plugin_dir_url( __FILE__ ).'css/BreakingNews.css' );
wp_enqueue_style( 'slr_plugin_style1' );

wp_enqueue_script( 'jquery' );

wp_register_script( 'slr_plugin_script2', plugins_url('/js/BreakingNews.js', __FILE__));
wp_enqueue_script( 'slr_plugin_script2' );

wp_register_script( 'slr_plugin_script1', plugins_url('/js/player.html5.js?r=4564564', __FILE__));
wp_enqueue_script( 'slr_plugin_script1' );

wp_register_script( 'slr_plugin_script3', "https://static.addtoany.com/menu/page.js");
wp_enqueue_script( 'slr_plugin_script3' );
  


?><meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Serverless Radio - Player</title>


<script type="text/javascript">

var volumez = <?php echo ($vol/10); ?>;
var autoplayz = "<?php echo $autoplay; ?>";

var siteurl = "<?php echo esc_url( home_url( '/' ) ); ?>";

</script>


<meta name="twitter:title" content="The Serverless Radio Platform (No VPS Required)">
<meta name="twitter:description" content="The Serverless Radio Platform (No VPS Required)">
<?php /* ?><meta name="twitter:card" content="summary"><?php */ ?>
<meta name="twitter:site" content="https://www.serverlessradio.com/">
<meta name="twitter:image" content="https://serverlessradio.com/player/v4/sls.jpg">
<meta name="twitter:creator" content="@HTML5MP3Player" />

<meta name="twitter:card" content="player" />
<meta name="twitter:url" content="<?php echo esc_url( home_url( '/' ) ); ?>?slr=<?php echo $slr; ?>" />
<meta name="twitter:player" content="<?php echo esc_url( home_url( '/' ) ); ?>?slr=<?php echo $slr; ?>" />
<meta name="twitter:player:stream" content="<?php echo esc_url( home_url( '/' ) ); ?>?slr=<?php echo $slr; ?>">
<meta name="twitter:player:stream:content_type" content="audio/mpeg">
<meta name="twitter:player:width" content="500" />
<meta name="twitter:player:height" content="150" />


 
<meta property="og:site_name" content="The Serverless Radio Platform (No VPS Required)">
<meta property="og:title" content="Serverless Radio" />
<meta property="og:description" content="The Serverless Radio Platform (No VPS Required)" />
<meta property="og:image" itemprop="image" content="https://serverlessradio.com/player/v4/sls.jpg">
<meta property="og:type" content="website" />


<div style="background-color: #000;">

<div style="display:none;">
  <audio id="slr_audio" name="slr_audio" preload controls>
    <source src="" type="audio/mpeg">
  </audio>
</div>

 
<div style="width: 100%">
        <div style="width: 20%; display: inline-block;">
            
            <ul style="width: 70px;" class="play-pause-icon">

			<li id="iz1" onClick="playBut();"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/b_play.png" style="cursor:pointer" border="0"></li>
			<li id="iz2" onClick="pauseBut();" style="display:none;"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>images/b_pause.png" style="cursor:pointer" border="0"></li>

            </ul> 

        </div>
        <div align="right" style="width: 79%; margin-right: 0px; margin-left: auto; justify-content: end;">
            
			
            <div class="social-share"> 
			<!-- AddToAny BEGIN -->

			  <div class="a2a_kit a2a_kit_size_30 a2a_default_style">

			  <a class="a2a_button_facebook"></a>
			  <a class="a2a_button_twitter"></a>
			  <a class="a2a_button_sms"></a>
			  <a class="a2a_button_linkedin"></a>
			  <a class="a2a_button_facebook_messenger"></a>
			  <a class="a2a_button_whatsapp"></a>
			  <a class="a2a_button_google_gmail"></a>			  

			  </div>

			  

			  <!-- AddToAny END -->
			</div>


        </div>
</div>
       
 

        
<div class="BreakingNewsController easing" id="breakingnews2">

			

        	<div class="bn-title"></div>
            <ul>
                
                <li><marquee scrollamount="2" behavior="scroll" direction="left" width="90%"><div style="color:<?php echo $textColor; ?>;" id="currtitle">Loading...</div></marquee></li>
                <li><marquee scrollamount="2" behavior="scroll" direction="left" width="90%"><div id="nexttitle">Loading...</div></marquee></li>
                <li><marquee scrollamount="2" behavior="scroll" direction="left" width="90%"><div id="prevtitle">Loading...</div></marquee></li>

            </ul>
            <div class="bn-arrows"><span class="bn-arrows-left"></span><span class="bn-arrows-right"></span></div>	
</div>


</div>


<div id="currTime"></div>