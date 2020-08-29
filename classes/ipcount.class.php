<?php
namespace Grav\Plugin;
use RocketTheme\Toolbox\File\File;
use Jaybizzle\CrawlerDetect\CrawlerDetect;  // TODO: this currently only works with jaybizzle/crawler-detect installed in Grav root, NOT local to plugin !
// DONE: 29.08.20 - see main file ipcount.php

class ipCount {

	//initiate the ipCount vars
	var $counter = 0;

	public function count($cache, $ip) {
		// see https://discourse.getgrav.org/t/best-practice-for-saving-managing-data-in-plugin/407/4
		session_start(); // Should always be on top
		if(!isset($_SESSION['counter'])) { // It's the first visit in this session
			$file = File::instance(DATA_DIR . 'counter/counter.txt');
			$counter = (int) $file->load();
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
				$counter++;
				$file->save($counter);
			}
			$_SESSION['counter'] = $counter;
			$_SESSION['isBot'] = $isBot;

		} else { // It's not the first time, do not update the counter
            $counter = $_SESSION['counter'];
        }
		$this->counter = $counter;
		return $this->counter;
	}
}
?>
