#!/usr/bin/env php
<?php
    /*
    This is a utility script which does something similar to logrotate, but for counter json files from grav plugin ipcount.
    Motivation is to not let grow the json file infinitely, but keep it at a reasonable size, but not lose older data.
    it reads the current data file, as well as a file with historic data, if that exists (if not, this will be created on the first run).
    current data contains the overall count, plus dayly count data.
    the current data file is command line argument No. 1.
    the historic data file Name is dreived from that, with .1.json naming convention in the same directory as the actual data file.
    the operation is based on $PastDays Value, whcih indicates, how many months data back from the call will be preserved in the current data file.
    all count data which is older, will be moved to the historic data file.
    If this already exists, data which is already contained there will be preserved, thus not losing any old data !
    Note that the historic data file will NOT contain total count data, only dayly counts.

    Here are the Command Line Options:
    -f  (required): CountFile - the File that contains the actual Count data (json Format)
    -d  (optional): the Number of days in the Past (from now) that should be kept in the reduced CountFile
    -r  (optional): Restore Mode, if given. in this case, -d is ignored and CountFile.0.json is created by combination of CountFle.1.json and CountFile.json
    */
    $verbose = false;
    $PastDays = 60;    // Days to preserve in current $CountFile, default Value = 60
    $TimeDiff = $PastDays * 24 * 60 * 60; // Time Difference in Seconds
    $shortopts = "f:d::r";
    $options = getopt($shortopts);
    if ($verbose) var_dump($options);
    if (!$options)
        die("Usage: ".$argv[0]." -f Countfile <-dDays> <-r>\n");
    $restore = false;
    $CountFile = null;
    // Handle command line arguments
    foreach (array_keys($options) as $opt) switch ($opt) {
        case 'f':
            $CountFile = $options['f'];
            break;
        case 'd':
            $PastDays = (int) $options['d'];
            break;
        case 'r':
            $restore = true;
            break;
    }
    if (!$CountFile)    die("Usage: ".$argv[0]." -f Countfile <-dDays> <-r>\n");
    /*
    var_dump($CountFile);
    var_dump($PastDays);
    var_dump($restore);
    */
    //  exit;
    $totalcount = 0;
    if ( file_exists($CountFile) ) {
        $json = file_get_contents($CountFile);
        $countdata = (array) json_decode($json, true);
        if ( $countdata === null ) {
            die ("no valid Countdata found, abort!\n");
        }
    } else {
        die ($CountFile." not found, abort!\n");
    }
    if ($verbose) var_dump($options);
    $totalcount = $countdata['count'];
    $CountPath = pathinfo($CountFile, PATHINFO_DIRNAME);
    if ($verbose)   var_dump($CountPath);
    $OldCountFile = $CountPath.'/'.pathinfo($CountFile, PATHINFO_FILENAME).'.1.json';
    if ($verbose)   var_dump($OldCountFile);
    $OldCountData = array();
    if ( file_exists($OldCountFile) ) {
        $json = file_get_contents($OldCountFile);
        $OldData = (array) json_decode($json, true);
        if ($OldData["days"])
            $OldCountData['days'] = $OldData["days"];
    }
    if ($restore)   {   // restore complete count data
        if (!$OldCountData['days']) die ("no valid restore data found, abort !\n");
        $resdata['count'] = $countdata['count'];
        $resdata['days'] = $OldCountData['days'];
        $cdata = $countdata["days"];
        while($day = current($cdata)) {
            //  var_dump(key($cdata));
            $resdata['days'][key($cdata)] = $cdata[key($cdata)];;
            next($cdata);
        }
        if ($verbose)   var_dump($resdata);
        $NewCountFile = $CountPath.'/'.pathinfo($CountFile, PATHINFO_FILENAME).'.0.json';
        $json = json_encode($resdata);
        if ($verbose)   var_dump($json);
        file_put_contents($NewCountFile, $json);
        exit;
    }
    //  var_dump($OldCountData);
    $today = idate("U");
    //  var_dump($today);
    $Until = $today - $TimeDiff;
    $DateUntil = date('ymd', $Until);
    //  var_dump($DateUntil);
    $cdata = $countdata["days"];
    //  var_dump($cdata);
    $NewData = array();
    $NewData['count'] = $totalcount;    // preserve totalcount
    while($day = current($cdata)) {
        //  var_dump(key($cdata));
        if ((key($cdata)) > ($DateUntil))   {
            $NewData['days'][key($cdata)] = $cdata[key($cdata)];;
        }   else    {
            $OldCountData['days'][key($cdata)] = $cdata[key($cdata)];
        }
        next($cdata);
    }
    //  var_dump($NewData);
    //  var_dump($OldCountData);
    $json = json_encode($OldCountData);
    if ($verbose)   var_dump($json);
    file_put_contents($OldCountFile, $json);
    //  exit;
    $json = json_encode($NewData);
    if ($verbose)   var_dump($json);
    file_put_contents($CountFile, $json);
?>
