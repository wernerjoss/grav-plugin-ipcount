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
			$counter++;
			$file->save($counter);
			$_SESSION['counter'] = $counter;
			
		} else { // It's not the first time, do not update the counter
	        $counter = $_SESSION['counter'];
        }
		$this->counter = $counter;
		return $this->counter;
	}
}
?>
