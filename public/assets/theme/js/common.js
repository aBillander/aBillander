/*
 * This file is part of aBillander
 * Copyright (C) 2015  Lara Billander  Lar.aBillander.com
 */


// Activate popovers!   http://www.tutorialrepublic.com/twitter-bootstrap-tutorial/bootstrap-popovers.php
$('[data-toggle="popover"]').popover({
  html : true
});


// Rounding to specified places

// https://stackoverflow.com/questions/11832914/round-to-at-most-2-decimal-places-only-if-necessary
// usage:var n = 1.7777;    n.round(2); // 1.78

// https://stackoverflow.com/questions/4912788/truncate-not-round-off-decimal-numbers-in-javascript

Number.prototype.round = function(places) {
  return +(Math.round(this + "e+" + places)  + "e-" + places);
}

// Numbers as string rounding. Groovy!!!
String.prototype.round = function(places) {
  return +(Math.round(parseFloat(this) + "e+" + places)  + "e-" + places);
}


// Show Alert Divs with a delay

var ALERT_MESSAGE_DELAY = 5000;

function showAlertDivWithDelay( targetDivID, seconds = null ) {

	if ( seconds == null ) seconds = (ALERT_MESSAGE_DELAY + 10) / 1000;
	// Equivalent to: if (variable === undefined || variable === null) {

	seconds = Math.floor(seconds+0.01);

	$(targetDivID).fadeIn().delay( seconds * 1000 ).fadeOut('slow');

	showDIV(targetDivID+"-counter", seconds);

}

function showDIV(targetDiv, i) {

    if (i < 0)
        return 
    setTimeout(showDIV, 1000, targetDiv, i-1);
    $(targetDiv).text(i);
}
                        

// **********************************************                    
