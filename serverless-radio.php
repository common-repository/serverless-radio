<?php

if ( ! defined( 'ABSPATH' ) ) {
        exit;
}


/*
Plugin Name: Serverless Radio 
Plugin URI: https://www.serverlessradio.com/
Description: Serverless Radio, MP3 Linear Playback, Radio Player, MP3 podcast Player, Audio Player, HTML5, Radiosolution, AutoDJ
Version: 0.7.8
Author: Sandeep Verma
Author URI: https://www.svnlabs.com/
*/ 


add_action( 'template_redirect', 'slr_callback' );
 
function slr_callback() {

global $post, $wpdb;

  $slr = sanitize_text_field($_REQUEST['slr']);

  if ( isset($slr) && $slr!="" ) {  //

   include_once("player/player.php");
   wp_footer();
   exit();
   
  }

  $slrtick = sanitize_text_field($_REQUEST['slrtick']);

  if ( isset($slrtick) && $slrtick!="" ) {  //

   include_once("player/events.php");
   exit();
   
  }


}



add_action( 'admin_footer', 'slr_ajax_scan_folder' );
function slr_ajax_scan_folder() { ?>
    <script type="text/javascript" >
    
    
    jQuery('#ScanFolder').click(function(){

        var dataVariable = {
            'action': 'slr_action_scan_folder',  
            'sourceurl': jQuery("#sourceurl").val()  
        };

        var playlist = '';


        jQuery('#toggleEmbed').html('');
        jQuery("#EmbedLocal").html('');


        jQuery("#slr_playlist").html( '<img src="<?php echo plugin_dir_url( __FILE__ ).'player/images/preloaderResult.gif'; ?>" /> Scanning...' );

        jQuery.ajax({
            url: ajaxurl, // this will point to admin-ajax.php
            type: 'POST',
            dataType : "json",
            data: dataVariable, 
            success: function (data) {               
           
              
              console.log(data);

              idno = data.length;

            
              playlist += '<div id="list"><ul  id="more_element_area">';

              for(i=0;i<data.length;i++)
              {


              mp3Duration(data[i].source, "duration"+data[i].index);  

              playlist += '<li class="grab" style="cursor: grab;" title="'+data[i].title+'" id="arrayorder_'+data[i].index+'">';
              
              playlist += '<i class="faarrows" style="font-size:12px; color: #666;"><img src="<?php echo plugin_dir_url( __FILE__ ).'player/images/dd.png'; ?>" border="0" /></i>&nbsp;';

              playlist += '<input class="form-control" style="width: auto; display: inline-block;" type="text" name="title[]" id="title'+data[i].index+'" value="'+data[i].title+'" placeholder="title" />&nbsp;';

              playlist += '<input class="form-control" style="width: auto; display: inline-block;" type="text" name="song[]" id="song'+data[i].index+'" value="'+data[i].source+'" placeholder="mp3" />&nbsp;';

              playlist += '<input class="form-control" style="width: auto; display: inline-block;" type="text" name="duration[]" id="duration'+data[i].index+'" value="" placeholder="duration" />&nbsp;';


              
              playlist += '<a title="Add More" href="javascript:void(0)" onclick="return addNewElement()">Add</a>&nbsp;';
              playlist += '<a title="Remove This" href="javascript:void(0)" onclick="return removeThisElement('+data[i].index+')">Remove</a>&nbsp;';

                          

              playlist += '<div style="clear: both;"></div></li>';

              }

              playlist += '</ul></div>';

              jQuery("#slr_playlist").html( playlist );


              if(typeof(idno)  === "undefined") 
               {
                 alert("Error Reading MP3 Folder Link, Please make sure your MP3 Folder Link is public!!");
                 jQuery("#slr_playlist").html( "Error Reading MP3 Folder Link, Please make sure your MP3 Folder Link is public!!" );
               } 


               jQuery( "#more_element_area" ).sortable();
               jQuery( "#more_element_area" ).disableSelection();



            },
            error: function(error) { // error logging

              
              alert("Error Reading MP3 Folder Link, Please make sure your MP3 Folder Link is public!!");
              console.log('Error!');
 
              jQuery("#slr_playlist").html( "Error Reading MP3 Folder Link, Please make sure your MP3 Folder Link is public!!" );

            }


        });

    });  
    
    </script> 
    <?php
}


add_action("wp_ajax_slr_action_scan_folder" , "slr_action_scan_folder");
function slr_action_scan_folder(){

    //echo json_encode($_POST);

    $folderfeedlink = sanitize_text_field($_REQUEST['sourceurl']);

    if(substr($folderfeedlink, -1)!="/")
      $folderfeedlink = $folderfeedlink."/";


    $ff = slr_read_folder($folderfeedlink, "mp3");

    $mm = 0;

    $array = array();
        
    foreach($ff as $file)
    {


    $array[] = array("index" => $mm, "title" => slr_file_name(basename($file)), "source" => $file, "picture" => "", "artist" => "", "duration" => "");

    $mm++;


    } 

    if($mm == 0)
     echo json_encode(array("error" => "Error Reading MP3 Folder Link"));
    else 
     echo json_encode($array);


    wp_die();
}





add_action( 'admin_footer', 'slr_get_player' );
function slr_get_player() { ?>
    <script type="text/javascript">

    var slr = "<?php echo time(); ?>";  

    jQuery('#GetPlayer').click(function(){

        var dataVariable = {
            'action': 'slr_action_get_player', 
            'slr' : slr,
            'playlistData': jQuery("#playlistFrm").serialize() 
        };

        jQuery.ajax({
            url: ajaxurl, // this will point to admin-ajax.php
            type: 'POST',
            dataType : "json",
            data: dataVariable, 
            success: function (data) {

                console.log(data.slr);

                var str = '<iframe src="<?php echo esc_url( home_url( '/' ) ); ?>?slr='+data.slr+'" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" width="100%" height="108"></iframe>'; 

                jQuery('#toggleEmbed').html('<textarea name="ecode" style="width:100%" cols="30" rows="3">'+str+'</textarea>');

                jQuery("#EmbedLocal").html(str);

            }
        });
    });

    </script> 
    <?php
}


add_action("wp_ajax_slr_action_get_player" , "slr_action_get_player");
function slr_action_get_player(){

    //print_r($_POST);

    parse_str($_POST['playlistData'], $slr_array);

    //print_r($slr_array);

    //wp_die();


    $slr = sanitize_text_field($_REQUEST['slr']);

    $titles = array_map( 'sanitize_text_field', wp_unslash( $slr_array['title'] ) );
    //$songs = array_map( 'sanitize_text_field', wp_unslash( $slr_array['song'] ) );

    $songs = $slr_array['song'];

    $durations = array_map( 'sanitize_text_field', wp_unslash( $slr_array['duration'] ) );

    //print_r($songs);


    if($slr!="")
    { 

    $i=0;

    $playlist = array();


    foreach ($titles as $key => $value) {
      
     $playlist[] = array("index" => $i, "title" => $value, "source" => $songs[$i], "picture" => "", "artist" => "", "duration" => $durations[$i]);

     $i++;

    }


    $echo = json_encode($playlist);

    $echo = str_replace("\/", "/", $echo);

    update_post_meta($slr, "slr_".$slr, $echo);


    //echo $time;

    echo json_encode(array("slr" => $slr));

    }



    wp_die();
}




function slr_get_current_song($startedAt, $slr)
{


//$elapsedTime = (time() - $startedAt);  

$elapsedTime = (time() - $slr);     

$list = json_decode( stripslashes(get_post_meta($slr, "slr_".$slr, true)), true);  

$array = array();

$i=0;

$totalDuration = 0;

foreach ($list as $l) {


//$mp3s = preg_replace('/ /i', '%20', $l['source']);

//$mp3s = mb_check_encoding($l['source'], 'UTF-8') ? $l : utf8_encode($l['source']);

$mp3s = $l['source'];   

$duration2 = $l['duration'];

$totalDuration += $duration2;

//$array[] = array( "id" => $i, "title" => $l['title'], "totaltime" => $duration2, "duration" => $duration2, "estimate" => $duration2 );

$array[] = array( "id" => $i, "title" => $mp3s, "estimate" => $duration2 );

$i++;

}

//print_r($array);

//echo $elapsedTime."-".$totalDuration." > ";

//echo intval($elapsedTime / $totalDuration)." > ";

$rem =  $elapsedTime % $totalDuration;

$temp = $rem;

$clist = count($array);

//print_r($array);

foreach($array as $k=>$ar)
{
   $temp = ($temp - $ar['estimate']);
   
   $next = $k+1; 
   if($next>=$clist) $next = 0;
   
   $prev1 = $k-1;
   if($prev1<0) $prev1 = $clist-1;
   
   $prev2 = $k-2;
   if($prev2<0) $prev2 = $clist-2;
   
   
   if($temp<0)
   {
     //echo $k." | ".$temp;
   return array("id" => $k, "title" => $ar['title'], "tick" => $ar['estimate']+$temp, "next" => $array[$next]['title'], "prev1" => $array[$prev1]['title'], "prev2" => $array[$prev2]['title']);
   break;

   }
    
}


}



function slr_read_folder($url, $fotmat="mp3")
{

$url = stripslashes(preg_replace('/ /i', '%20', $url)); 

//$html = file_get_contents($url); 

$html = "";

$response = wp_remote_get( $url );
 
if ( is_array( $response ) && ! is_wp_error( $response ) ) {
    $headers = $response['headers']; // array of http header lines
    $html    = $response['body']; // use the content
}



if($html=="") echo "<strong>If Audio MP3 Folder is not public...</strong>";

$mp3s = array();

$dom = new DOMDocument();
@$dom->loadHTML($html);

$xpath = new DOMXPath($dom);

//$tRows = $xpath->query("//a[ends-with(@href,'.mp3')]");

if($fotmat=="aac")
 $tRows = $xpath->query('//a[contains(@href, ".aac")]//@href');
else if($fotmat=="mp3")
 $tRows = $xpath->query('//a[contains(@href, ".mp3")]//@href');
else
 $tRows = $xpath->query('//a[contains(@href, ".mp4")]//@href');

//print_r($tRows->nodeValue);

foreach ($tRows as $row) {
    // fetch all 'tds' inside this 'tr'
    //$td = $xpath->query('td', $row);
  
  if(preg_match('#http#', $row->nodeValue))
   $nodeValue = $row->nodeValue; 
  else
   $nodeValue = str_replace("list.php", "", $url).$row->nodeValue;
  
  //echo $row->textContent."<br>";
  //echo $nodeValue."<br>";
  
  //$mp3ss[] = $nodeValue;
  
  $mp3s[] = $nodeValue;
  
} 

return $mp3s;

} 



function slr_file_name($s)
{

  $s = str_replace("%20", " ", $s);
  $s = str_replace(".mp3", "", $s);
  $s = str_replace(".MP3", "", $s);
  $s = str_replace("_", " ", $s);
  $s = str_replace("-", " ", $s);
  $s = str_replace("+", " ", $s);
  $s = str_replace("  ", " ", $s);

  return urldecode($s);

}


// Start the plugin
if ( ! class_exists( 'Serverless_Radio' ) ) {

class Serverless_Radio {
        
// prep options page insertion
function add_config_page() {
	if ( function_exists('add_submenu_page') ) {
		add_options_page('Serverless Radio', 'Serverless Radio', 10, basename(__FILE__), array('Serverless_Radio','config_page'));
	}	
}
        
// Options/Settings page in WP-Admin
function config_page() {

			
?>

<div class="wrap">
<h2>Serverless Radio Player</h2>


<div style="color: #ff0000">Note: Please enter MP3 folder HTTPS link like (https://domain.com/songs/).</div>


<div style="clear: both;">&nbsp;</div>
  
  
<form action="" method="post" name="sourceFrm" id="sourceFrm">

	
<input type="text" name="sourceurl" id="sourceurl" style="width: 60%; display: inline-block;" value="">&nbsp;<input type="button" id="ScanFolder" style="width: auto; display: inline-block;" name="ScanFolder" value="Scan Folder">

<br><br>OR Make Serverless Radio Playlist Manually Below<br><br>
	
</form>
 
<div><b>MP3 Title || MP3 Link || MP3 Duration</b></div> 

<form action="" method="post" name="playlistFrm" id="playlistFrm">

<div align="left" id="slr_playlist">

<!-- Manual Playlist  -->
<script type="text/javascript"> idno = 0; </script>

    <div id="list"><ul  id="more_element_area">

    <li class="grab" style="cursor: grab;" title="" id="arrayorder_0">

    <i class="faarrows" style="font-size:12px; color: #666;"><img src="<?php echo plugin_dir_url( __FILE__ ).'player/images/dd.png'; ?>" border="0" /></i>&nbsp;

    <input class="form-control" style="width: auto; display: inline-block;" type="text" name="title[]" id="title0" value="" onchange="setLiTitle('0');" placeholder="title" />&nbsp;

    <input class="form-control" style="width: auto; display: inline-block;" type="text" name="song[]" id="song0" value="" onchange="mp3DurationElement(`0`);" placeholder="mp3" />&nbsp;

    <input class="form-control" style="width: auto; display: inline-block;" type="text" name="duration[]" id="duration0" value="" placeholder="duration" />&nbsp;



    <a title="Add More" href="javascript:void(0)" onclick="return addNewElement()">Add</a>&nbsp;
    <a title="Remove This" href="javascript:void(0)" onclick="return removeThisElement('0')">Remove</a>&nbsp;

                
    <div style="clear: both;"></div></li>


    </ul></div>
<!-- Manual Playlist  -->


</div>

<div style="clear: both;">&nbsp;</div>

<input type="button" name="GetPlayer" id="GetPlayer" value="Get Player">

</form>


<div style="clear: both;">&nbsp;</div>

- Button "Scan Folder" will scan MP3 folder for reading duration of each MP3 file(s) <br>
- Button "Get Player" will make Serverless Radio 


<div style="clear: both;">&nbsp;</div>


<div align="center" id="EmbedLocal"></div>  


<div align="center" id="toggleEmbed" style="display:block; z-index:1000; overflow:hidden;"></div>

 


<strong><a href="https://www.serverlessradio.com/" target="_blank">Serverless Radio Player</a></strong>



 </div>
<?php		

       }
	}
} 
  

wp_register_script( 'slr_plugin_script4', plugins_url('/js/serverless.js', __FILE__));
wp_enqueue_script( 'slr_plugin_script4' ); 

// Jquery UI Core
//wp_enqueue_script('jquery-ui-core'); 

// Jquery Sortable
wp_register_script( 'jquery-sortable', plugins_url('/js/jquery.sortable.js', __FILE__), null, null, true );
wp_enqueue_script( 'jquery-sortable' );

// insert into admin panel
add_action('admin_menu', array('Serverless_Radio','add_config_page'));


?>