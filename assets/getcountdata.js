document.addEventListener('DOMContentLoaded', function() {
    // diese TWIG Funktion verhindert die Auslagerung des scripts in eine standalone js Datei.
    // datafile = '{{page.header.datafile}}';
    // DONE: Auslagern und Datenübergabe z.B. mittels Ajax, siehe
    // https://stackoverflow.com/questions/23740548/how-do-i-pass-variables-and-data-from-php-to-javascript
    // so gehts einfach über das DOM:
    var verbose = false;
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
    var x = []; var y = [];
    jQuery.each(days, function(index, item) {
        if (verbose)    console.log(index, item);
        dstr = index.toString();
        datum =  dstr.substr(4,2) + '.' + dstr.substr(2,2) + '.' + dstr.substr(0,2);
        if (verbose)    console.log(datum);
        x.push(datum);
        y.push(item);
    });
    GRAPH = document.getElementById('daydata');
    var layout = {
        title: 'Dayly Visitors Count',
        showlegend: false
    };

    var trace1 = {
        type: 'bar',
        x,
        y 
    };

    var data = [trace1];
    Plotly.newPlot( GRAPH, data, layout);
});
function getHomeUrl(verbose) {    // see https://stackoverflow.com/questions/25203124/how-to-get-base-url-with-jquery-or-javascript - slightly modified :-)
    var orgUrl = window.location.origin;
    if (verbose)    console.log('window.location.origin',window.location.origin);
    var getUrl = window.location;
    var baseUrl = orgUrl + getUrl.pathname.split('/')[0];
    if (verbose)    console.log('baseUrl:', baseUrl);
    return baseUrl;
}
