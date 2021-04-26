<?php
namespace Grav\Plugin\IPCount;

use Grav\Common\Session;
use RocketTheme\Toolbox\File\File;
use Jaybizzle\CrawlerDetect\CrawlerDetect;  // TODO: this currently only works with jaybizzle/crawler-detect installed in Grav root, NOT local to plugin !

// DONE: 29.08.20 - see main file ipcount.php
// new: 29.01.21	-	save dayly count data in yaml file
// new: 30.01.21	-	save dayly count data in json file (yaml access will be blocked due to security restrictions)
// TODO: admin Interface to visualize historic count data

class IPCounter {

	//initiate the ipCount vars
	var $counter = 0;
	var $daycount = 0;

	public function count($cache, $ip) {
		//	see https://discourse.getgrav.org/t/best-practice-for-saving-managing-data-in-plugin/407/4
		$path = DATA_DIR . 'counter/counter.json';	// json file from 30.01.21 !
		$countdata = array();
		$jsonfile = File::instance($path);
		$sess = Session::getInstance();
		$sess->start();	// instead of session_start()
		$cvar = $sess->__isset('counter');
		if(!$cvar) {	// do NOT use $_SESSION here when working with grav Yaml File Classes, use Grav'S Session class instead !
			$today = date("ymd");
			if ( file_exists($path) ) {
				$json = file_get_contents($path);
				$countdata = (array) json_decode($json, true);
				if ( $countdata === null ) {
					$countdata = array();
					$countdata['count'] = 0;
					$countdata["days"][$today] = 0;
				}
			} else {
				$countdata = array();
				$countdata['count'] = 0;
				$countdata["days"][$today] = 0;
				$jsonfile->save(json_encode($countdata));
				chmod($path, 0664);
			}
			$count = (int) $countdata['count'];
			try {
				$daycount = (int) $countdata["days"][$today];	// this might fail, if file exists, but has no daycount data
			}	catch (\Exception $e) {
				$daycount = 0;
			}
			$isBot = false;
			// basic crawler detection script (no legit browser should match this)
			if(!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])){
				// this is a crawler and counter should not be updated	-> do nothing
				$isBot = true;
			}
			$CrawlerDetect = new CrawlerDetect;	// improved crawler detection
			// Check the user agent of the current 'visitor'
			if($CrawlerDetect->isCrawler()) {
				// true if crawler user agent detected
				$isBot = true;
			}
			//	dump("isBot: ".$isBot."counter:".$counter);
			if (!$isBot) {
				$count++;
				$daycount++;
				$countdata['count'] = $count;
				$countdata["days"][$today] = $daycount;
				$jsonfile->save(json_encode($countdata));
			}
			$sess->__set('counter', $count);
			$_SESSION['counter'] = $count;
			$_SESSION['isBot'] = $isBot;

		} else { // It's not the first time, do not update the counter
			$count = $sess->__get('counter');	// see above $_SESSION remarks...
		}
		$this->counter = $count;
		return $this->counter;
	}
}
?>