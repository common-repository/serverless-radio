function mp3DurationElement(id)
{

 var url = document.getElementById("song"+id).value;

 mp3Duration(url, "duration"+id);

} 

function mp3Duration(url, ele)  
{

// Create a non-dom allocated Audio element
var au = document.createElement('audio');

// Define the URL of the MP3 audio file
au.src = url;

// Once the metadata has been loaded, display the duration in the console
au.addEventListener('loadedmetadata', function(){
    // Obtain the duration in seconds of the audio file (with milliseconds as well, a float value)

    //console.log("au: " + au.audioTracks);

    var duration = au.duration;

    // example 12.3234 seconds
    console.log("The duration of the song is of: " + duration + " seconds");

    //return duration;

    jQuery("#"+ele).val( duration.toFixed(0) );

    // Alternatively, just display the integer value with
    // parseInt(duration)
    // 12 seconds
},false);


} 



//var idno = 2 // It start from id 2 

function addNewElement()
{

	//if(idno==null || idno=="") idno = 2;

	// mainDiv is a variable to store the object of main area Div.
	var mainDiv = document.getElementById('more_element_area');
	// Create a new div 
	var innerDiv = document.createElement('li');
	// Set the attribute for created new div like here I am assigning Id attribure. 
	innerDiv.setAttribute('id', 'arrayorder_' + idno);
	// Create text node to insert in the created Div
		

	var generatedContent = '';

    generatedContent += '<i class="fa fa-arrows" style="font-size:12px; color: #666;"></i>&nbsp;';

    generatedContent += '<i class="faarrows" style="font-size:12px; color: #666;"><img src="../wp-content/plugins/serverless-radio/player/images/dd.png" border="0" /></i>&nbsp;';

	generatedContent += '<input class="form-control" style="width: auto; display: inline-block;" type="text" name="title[]" id="title'+idno+'" value="" onchange="setLiTitle('+idno+');" placeholder="title" />&nbsp;';
    
    generatedContent += '<input class="form-control" style="width: auto; display: inline-block;" type="text" name="song[]" id="song'+idno+'" value="" placeholder="mp3" onchange="mp3DurationElement(`'+idno+'`);" />&nbsp;';
    
    generatedContent += '<input class="form-control" style="width: auto; display: inline-block;" type="text" name="duration[]" id="duration'+idno+'" value="" placeholder="duration" />&nbsp;';

    
    generatedContent += '<a title="Add More" href="javascript:void(0)" onclick="return addNewElement()">Add</a>&nbsp;';
    generatedContent += '<a title="Remove This" href="javascript:void(0)" onclick="return removeThisElement('+idno+')">Remove</a>&nbsp;';
	
	
	// Inserting content to created Div by innerHtml
	innerDiv.innerHTML = generatedContent;

	// Appending this complete div to main div area.
	mainDiv.appendChild(innerDiv);

    

    var tmp = document.getElementById('arrayorder_' + idno);

	tmp.setAttribute('title', document.getElementById('title' + idno).value);

	tmp.setAttribute('class', 'grab ui-sortable-handle');

    // Focus on new element
    document.getElementById('title' + idno).focus();


	// increment the id number by 1.
	idno++;


	jQuery( "#more_element_area" ).sortable();
    jQuery( "#more_element_area" ).disableSelection();


}

function removeThisElement(idnum)
{
	
	if(confirm("Are you Sure?"))
	{
	// mainDiv is a variable to store the object of main area Div.
	var mainDiv = document.getElementById('more_element_area');
	// get the div object with get Id to remove from main div area.
	var innerDiv = document.getElementById('arrayorder_' + idnum);
	// Removing element from main div area.
	mainDiv.removeChild(innerDiv);
	}
 
}


function setLiTitle(idno)
{

var tmp = document.getElementById('arrayorder_' + idno);	

tmp.setAttribute('title', document.getElementById('title' + idno).value);

}