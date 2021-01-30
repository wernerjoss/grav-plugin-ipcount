document.addEventListener('DOMContentLoaded', function() {
    // diese TWIG Funktion verhindert die Auslagerung des scripts in eine standalone js Datei.
    // datafile = '{{page.header.datafile}}';
    // DONE: Auslagern und Datenübergabe z.B. mittels Ajax, siehe
    // https://stackoverflow.com/questions/23740548/how-do-i-pass-variables-and-data-from-php-to-javascript
    // so gehts einfach über das DOM:
    datafile = jQuery('#datafile').text();
    dataUrl = getAbsolutePath() + 'user/data/counter/' + datafile;
    console.log(dataUrl);
    var request = new XMLHttpRequest();
    request.open("GET",dataUrl, false);
    request.send(null);
    var nativeObject = JSON.parse(request.responseText);
    
    console.log('json data:', nativeObject);
    var days = nativeObject['days'];
    var x = []; var y = [];
    jQuery.each(days, function(index, item) {
        console.log(index, item);
        dstr = index.toString();
        datum =  dstr.substr(4,2) + '.' + dstr.substr(2,2) + '.' + dstr.substr(0,2);
        console.log(datum);
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
function getAbsolutePath() { // see https://www.sitepoint.com/jquery-current-page-url/
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));
}
