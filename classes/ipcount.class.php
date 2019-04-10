<?php

namespace Grav\Plugin;
use RocketTheme\Toolbox\File\File;

class ipCount {
	
	//initiate the ipCount vars
	var $counter = 0;
	
	function count($cache, $ip) {
		// see https://discourse.getgrav.org/t/best-practice-for-saving-managing-data-in-plugin/407/4
		$file = File::instance(DATA_DIR . 'counter/counter.txt');
		if(!isset($_SESSION['counter'])) { // It's the first visit in this session, so count us as new visitor
			$counter = (int) $file->load();
			$counter++;
			$file->save($counter);
			$_SESSION['counter'] = $counter;
			//set the counter vars
			$this->counter = $counter;
			return $this->counter;
		}
	}
}
?>
