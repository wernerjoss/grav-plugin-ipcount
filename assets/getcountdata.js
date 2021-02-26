var verbose = true; // global
var submonths = 1;  // initial value

jQuery(document).ready(function () {
	// DONE: Auslagern und Datenübergabe z.B. mittels Ajax, siehe
	// https://stackoverflow.com/questions/23740548/how-do-i-pass-variables-and-data-from-php-to-javascript
	// so gehts einfach über das DOM:
	datafile = jQuery('#datafile').text();
	home = jQuery('#homeurl').text();   // this is the home directory below the base URL, '/' if Grav is installed in base URL 
	if (verbose)    console.log('home:', home);
	if (home == '/')    home = '';  // discard if this is only '/' (do not produce // in dataUrl)
	dataUrl = getHomeUrl(verbose) + home + '/user/data/counter/' + datafile;
	if (verbose)    console.log('dataUrl:', dataUrl);
	var request = new XMLHttpRequest();
	request.open("GET",dataUrl, false);
	request.send(null);
	var nativeObject = JSON.parse(request.responseText);

	var today = moment();
	var monthfilter = today.format("YYMM");

	filterData(monthfilter, nativeObject);
});

function filterData(monthfilter, nativeObject) {
	if (verbose)    console.log('monthfilter:', monthfilter);
	if (verbose)    console.log('json data:', nativeObject);
	var days = nativeObject['days'];
	var x = []; var y = [];
	jQuery.each(days, function(index, item) {
		if (verbose)    console.log(index, item);
		dstr = index.toString();
		datum =  dstr.substr(4,2) + '.' + dstr.substr(2,2) + '.' + dstr.substr(0,2);
		monthsig = dstr.substr(0,2) + dstr.substr(2,2);
		if (verbose)    console.log('Datum:', datum, 'MSig:', monthsig);
		if (monthfilter.valueOf() == monthsig.valueOf())    {
			x.push(datum);
			y.push(item);
		}
	});
	GRAPH = document.getElementById('daydata');
	month = monthfilter.substr(2, 2);
	year = monthfilter.substr(0, 2);
	Title = 'Dayly Visitors Count for ' + month + ' / ' + year;
	var layout = {
		title: Title,
		showlegend: false
	};

	var trace1 = {
		type: 'bar',
		x,
		y 
	};

	var data = [trace1];
	Plotly.newPlot( GRAPH, data, layout);
}

function getHomeUrl(verbose) {    // see https://stackoverflow.com/questions/25203124/how-to-get-base-url-with-jquery-or-javascript - slightly modified :-)
	var orgUrl = window.location.origin;
	if (verbose)    console.log('window.location.origin',window.location.origin);
	var getUrl = window.location;
	var baseUrl = orgUrl + getUrl.pathname.split('/')[0];
	if (verbose)    console.log('baseUrl:', baseUrl);
	return baseUrl;
}

function subMonth() {
	if (verbose)    console.log('monthfilter:', monthfilter);
	datafile = jQuery('#datafile').text();
	home = jQuery('#homeurl').text();   // this is the home directory below the base URL, '/' if Grav is installed in base URL 
	if (verbose)    console.log('home:', home);
	if (home == '/')    home = '';  // discard if this is only '/' (do not produce // in dataUrl)
	dataUrl = getHomeUrl(verbose) + home + '/user/data/counter/' + datafile;
	if (verbose)    console.log('dataUrl:', dataUrl);
	var request = new XMLHttpRequest();
	request.open("GET",dataUrl, false);
	request.send(null);
	var nativeObject = JSON.parse(request.responseText);

	if (verbose)    console.log('json data:', nativeObject);
	var days = nativeObject['days'];
	mm = moment().subtract(submonths, 'months').format("YYMM");
	submonths = submonths + 1;  // update for next click !
	//  alert('MM Sub:' + mm);
	if (verbose)    console.log("submonths:", submonths);
	var monthfilter = mm;

	filterData(monthfilter, nativeObject);
}

function toDay() {
	if (verbose)    console.log('monthfilter:', monthfilter);
	datafile = jQuery('#datafile').text();
	home = jQuery('#homeurl').text();   // this is the home directory below the base URL, '/' if Grav is installed in base URL 
	if (verbose)    console.log('home:', home);
	if (home == '/')    home = '';  // discard if this is only '/' (do not produce // in dataUrl)
	dataUrl = getHomeUrl(verbose) + home + '/user/data/counter/' + datafile;
	if (verbose)    console.log('dataUrl:', dataUrl);
	var request = new XMLHttpRequest();
	request.open("GET",dataUrl, false);
	request.send(null);
	var nativeObject = JSON.parse(request.responseText);
	mm = moment().format("YYMM");   // today !
	//  alert('MM Sub:' + mm);
	if (verbose)    console.log("MM Sub:", mm);
	var monthfilter = mm;
	submonths = 1;  // default value

	filterData(monthfilter, nativeObject);
}
