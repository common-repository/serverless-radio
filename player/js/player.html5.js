$ = jQuery;

$(window).load(function(){

//var audioControl = document.getElementsByTagName('slr_audio')[0];

var audioControl = document.getElementById('slr_audio');

audioControl.volume = volumez;

//playBut();

$('.muted').click(function () {
    audioControl.muted = !audioControl.muted;
    return false;
});

//VOLUME BAR
//volume bar event
var volumeDrag = false;
$('.volume,.volumeBar').on('mousedown', function (e) { //onTouchStart
    volumeDrag = true;
    audioControl.muted = false;
    $('.sound').removeClass('muted');
    updateVolume(e.pageX);
});
$(document).on('mouseup', function (e) { //touchend
    if (volumeDrag) {
        volumeDrag = false;
        updateVolume(e.pageX);
    }
});
$(document).on('mousemove', function (e) { //touchmove
    if (volumeDrag) {
        updateVolume(e.pageX);
    }
});

var updateVolume = function (x, vol) {
    var volume = $('.volume');
    var percentage;
    //if only volume have specificed
    //then direct update volume
    if (vol) {
        percentage = vol * 220;
    } else {
        var position = x - volume.offset().left;
        percentage = 220 * position / volume.width();
    }

    if (percentage > 220) {
        percentage = 220;
    }
    if (percentage < 0) {
        percentage = 0;
    }

    //update volume bar and video volume
    $('.volume').css('clip', 'rect(0px, '+percentage+'px, 55px, 0px)')
    //$('.volume').css('width', percentage + '%');
    
	audioControl.volume = (percentage / 220);  //console.log( percentage / 220 );
    
    //console.log(percentage);

    //change sound icon based on volume
    if (audioControl.volume == 0) {
        $('.sound').removeClass('sound2').addClass('muted');
    } else if (audioControl.volume > 0.5) {
        $('.sound').removeClass('muted').addClass('sound2');
    } else {
        $('.sound').removeClass('muted').removeClass('sound2');
    }

};
});





function playBut()
{
document.getElementById('slr_audio').play();
document.getElementById('iz1').style.visibility='hidden';
document.getElementById('iz1').style.display='none';
document.getElementById('iz2').style.visibility='visible';
document.getElementById('iz2').style.display='block';
autoplayz = "true";  /// autostart next song if autoplay is false
}

function pauseBut()
{
document.getElementById('slr_audio').pause();
document.getElementById('iz2').style.visibility='hidden';
document.getElementById('iz2').style.display='none';
document.getElementById('iz1').style.visibility='visible';
document.getElementById('iz1').style.display='block';
}
	
	
function getQueryStrings() { 
  var assoc  = {};
  var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
  var queryString = location.search.substring(1); 
  var keyValues = queryString.split('&'); 

  for(var i in keyValues) { 
    var key = keyValues[i].split('=');
    if (key.length > 1) {
      assoc[decode(key[0])] = decode(key[1]);
    }
  } 

  return assoc; 
} 	
	
	
function basename (path, suffix) {

  var b = path
  var lastChar = b.charAt(b.length - 1)

  if (lastChar === '/' || lastChar === '\\') {
    b = b.slice(0, -1)
  }

  b = b.replace(/^.*[\/\\]/g, '')

  if (typeof suffix === 'string' && b.substr(b.length - suffix.length) === suffix) {
    b = b.substr(0, b.length - suffix.length)
  }

  return b
}
	
	if (!Date.now) {
	Date.now = function() { return new Date().getTime(); };
	}
	
	
	
	var currentTime = Date.now() || +new Date();
	
	

	var qs = getQueryStrings();
	
      //var es = new EventSource("events.php?jdata="+qs['jdata']);

      //alert(qs['file']);



      var poll = function() {
          $.ajax({
            url: siteurl+"?slrtick="+qs['slr'],
            dataType: 'json',
            type: 'get',
            success: function(data) { // check if available
              
              console.log(data.live);


     
		
		//document.body.innerHTML = (div);
		
		/////////================================================

		
        //console.log(data);
		
		var player = document.getElementById('slr_audio');
		
		//document.getElementById('currsong').innerHTML = data.song;
		
		//alert(player.getAttribute('name'));
		
		// don't load html data if it is playing 
		if(data.id.toString() != player.getAttribute('name'))
		{
			
			
		var ttitle = data.song;
		ttitle = basename(ttitle);  
		ttitle = ttitle.replace(".mp3", ""); 
		ttitle = ttitle.replace(/[^a-zA-Z ]/g, " ");
		
		
		document.title = "ServerlessRadio.com | "+ttitle;
		//document.getElementById('current-track').innerHTML = ttitle;
		
		//document.getElementById('currtitle').innerHTML = "Now Playing: "+ttitle;
		//document.getElementById('currartist').innerHTML = "";
		document.getElementById('currtitle').innerHTML = "ServerlessRadio.com | "+ttitle;


		var ntitle = basename(data.next);
		ntitle = ntitle.replace(".mp3", "");
		ntitle = ntitle.replace(/[^a-zA-Z ]/g, " ");
		

		//alert(ntitle);
		

		//document.getElementById('nexttitle').innerHTML = "Next: " + ntitle;
		document.getElementById('nexttitle').innerHTML = "ServerlessRadio.com | "+ntitle;


		var ptitle = basename(data.prev1);
		ptitle = ptitle.replace(".mp3", ""); 
		ptitle = ptitle.replace(/[^a-zA-Z ]/g, " ");

		//document.getElementById('prevtitle').innerHTML = "Previous: " + ptitle;
		document.getElementById('prevtitle').innerHTML = "ServerlessRadio.com | "+ptitle;

		
		
		//getInfo(data.next, "nextphoto", "nextinfo");
		//getInfo(data.prev1, "prevphoto1", "previnfo1");
		//getInfo(data.prev2, "prevphoto2", "previnfo2");
		

        player.setAttribute('src', data.song);
		player.setAttribute('name', data.id.toString());
        player.currentTime = parseInt(data.tick);
		
		if(autoplayz=="true")
		{
		
		player.load();
        //player.play();
		
		if(!isMobile())
		 playBut();
		 
			
		}
		
		var nextSong = data.next;
		
		//alert(autoplayz);
		
		
		if(autoplayz=="true")
		{
		
		if(isMobile())
		{
		 
		 autoNext(nextSong);
		 //playButMob();
		 		 
		}
		else
		{
		 
		 autoNext(nextSong);
		 //playBut();
		 
		}
		
		}
		
		//alert(data.next);
		
		
		
		//player.addEventListener('ended', function(nextSong){ alert(nextSong); }, false);
		 
		
		//$(".progress-bar-danger").css("width", "0px");
		
		//document.getElementById('current-time').innerHTML = data.tick;
		
		}
		
		//document.getElementById('currTime').innerHTML =( Math.round(player.currentTime) +"="+ data.tick );
		//console.log( Math.round(player.currentTime) +"="+ parseInt(data.tick) );
		
		///if( data.tick - Math.round(player.currentTime) > 3 )
		///if(Math.round(player.currentTime) != data.tick)
		
		
		/* Sync Song with SSE TICK */
		if( parseInt(data.tick) - Math.round(player.currentTime) > 3 )  
		{
		
		  player.currentTime = parseInt(data.tick);
			
		}
		
		
		 ////////////=======================================================


              if ( data.live ) { // get and check data value
                
                clearInterval(pollInterval); // optional: stop poll function

              }
            },
            error: function() { // error logging
              console.log('Error!');
            }
          });
        },
        pollInterval = setInterval(function() { // run function every 2000 ms
          poll();
          }, 10000);
        
        poll(); // also run function on init
		
 
		
		
    
	  
	  
	function isPlaying(player) {
    //var player = document.getElementById(playerId);
    return !player.paused && !player.ended && 0 < player.currentTime;
    }
	
	
	function isPlayingMob(player)
	{
	return !player.paused;
	}
	
 
     function basename(path) {

     // decodeURIComponent  or decodeURI
     return decodeURIComponent(path.replace(/.*\//, ''));
    }
 
    
	
	
	//true / false
	function isMobile()
	{
	return (/Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent) ); 
	}


	
	

	
	
	var firstTime=0;
	
	function autoNext(nextSong)
	{
	
 
	
	var myAudio = document.getElementById("slr_audio");
	
	myAudio.play();
	
 
	
	function loopAudioPlay() {
		var myAudio = document.getElementById("slr_audio");
		myAudio.pause();
		//myAudio.currentTime=0.0;
		//myAudio.volume = 0;
		myAudio.setAttribute("src", nextSong);  //alert(nextSong);
		
		setTimeout(function () {      
		  myAudio.play();
		}, 150);
		
		//myAudio.play();		
	}
	
	myAudio.addEventListener('ended', loopAudioPlay, false);
	}
	
	
	function pad2(number) {
    return (number < 10 ? '0' : '') + number
   }
	

jQuery("#breakingnews2").BreakingNews({
	background		:'#FFF',
	title			:'Playing',
	titlecolor		:'#000',
	titlebgcolor	:'#fff',
	linkcolor		:'#333',
	linkhovercolor	:'#099',
	fonttextsize	:16,
	isbold			:true,
	//border		:'solid 1px #fff',
	width			:'100%',
	timer			:10000,
	autoplay		:true,
	effect			:'slide'
		
});	