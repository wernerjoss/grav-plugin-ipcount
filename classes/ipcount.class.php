<?php
namespace Grav\Plugin;
use Grav\Common\Plugin;
use Grav\Common\Yaml;
use Grav\Common\Session;
use RocketTheme\Toolbox\File\File;
use Jaybizzle\CrawlerDetect\CrawlerDetect;  // TODO: this currently only works with jaybizzle/crawler-detect installed in Grav root, NOT local to plugin !
// DONE: 29.08.20 - see main file ipcount.php

class ipCount {

	//initiate the ipCount vars
	var $counter = 0;

	public function count($cache, $ip) {
		//	see https://discourse.getgrav.org/t/best-practice-for-saving-managing-data-in-plugin/407/4
		//	session_start(); // Should always be on top
		$path = DATA_DIR . 'counter/counter.yaml';	// yaml file from 22.01.21 !
		$countdata = array();
		$yamlfile = File::instance($path);
		$sess = Session::getInstance();
		$sess->start();	// instead of session_start()
		//	if(!isset($_SESSION['counter'])) { // It's the first visit in this session
		$cvar = $sess->__isset('counter');
		if(!$cvar) {	// do NOT use $_SESSION here when working with grav Yaml File Classes, use Grav'S Session class instead !
			/*	obsolete from 22.01.21:
			$file = File::instance(DATA_DIR . 'counter/counter.txt');
			$counter = (int) $file->load();
			*/
			if ( file_exists($path) ) {
				$countdata = Yaml::parse($yamlfile->content());
				if ( $countdata === null ) {
					$countdata = array();
					$countdata['count'] = 0;
				}
			} else {
				$countdata = array();
				$countdata['count'] = 0;
				$yamlfile->save(Yaml::dump($countdata));
				chmod($path, 0664);
			}
			$count = (int) $countdata['count'];

			$isBot = false;
			// basic crawler detection script (no legit browser should match this)
			if(!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])){
				// this is a crawler and counter should not be updated	-> do nothing
				$isBot = true;
			}
			$CrawlerDetect = new CrawlerDetect;	// improved crawler detection
			//	$this->grav = Grav::instance;
			//	$this->grav['log']->info("IsCrawler:".$CrawlerDetect->isCrawler());	// this does not work !
			// Check the user agent of the current 'visitor'
			if($CrawlerDetect->isCrawler()) {
				// true if crawler user agent detected
				$isBot = true;
			}
			//	dump("isBot: ".$isBot."counter:".$counter);
			if (!$isBot) {
				$count++;
				//	$file->save((string)$counter);	// Typcast mandatory since Grav 1.7 ! 21.01.21
				$countdata['count'] = $count;
				$yamlfile->save(Yaml::dump($countdata));	// use yaml now to avoid future problems like 2 lines above :)
			}
			$sess->__set('counter', $count);
			$_SESSION['counter'] = $count;
			$_SESSION['isBot'] = $isBot;

		} else { // It's not the first time, do not update the counter
			//	$count = $_SESSION['counter'];
			$count = $sess->__get('counter');	// see above $_SESSION remarks...
		}
		$this->counter = $count;
		return $this->counter;
	}
}
?>
