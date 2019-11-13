<?php

namespace Grav\Plugin;
use RocketTheme\Toolbox\File\File;

class ipCount {
	
	//initiate the ipCount vars
	var $counter = 0;
	
	function count($cache, $ip) {
		// see https://discourse.getgrav.org/t/best-practice-for-saving-managing-data-in-plugin/407/4
		session_start(); // Should always be on top
		if(!isset($_SESSION['counter'])) { // It's the first visit in this session
			$file = File::instance(DATA_DIR . 'counter/counter.txt');
			$counter = (int) $file->load();
			// basic crawler detection script (no legit browser should match this)
			if(!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])){
				// this is a crawler and counter should not be updated	-> do nothing
			} else {
				$counter++;
				$file->save($counter);
			}
			$_SESSION['counter'] = $counter;
			
		} else { // It's not the first time, do not update the counter
	        $counter = $_SESSION['counter'];
        }
		$this->counter = $counter;
		return $this->counter;
	}
}
?>
