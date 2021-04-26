<?php

namespace Grav\Plugin\IPCount;

use Grav\Common\Session;
use RocketTheme\Toolbox\File\File;
use Jaybizzle\CrawlerDetect\CrawlerDetect;  // TODO: this currently only works with jaybizzle/crawler-detect installed in Grav root, NOT local to plugin !

// DONE: 29.08.20 - see main file ipcount.php
// new: 29.01.21	-	save dayly count data in yaml file
// new: 30.01.21	-	save dayly count data in json file (yaml access will be blocked due to security restrictions)
// TODO: admin Interface to visualize historic count data

class IPCounter
{

	//initiate the ipCount vars
	const DATAFILE = DATA_DIR . 'counter/counter.json';

	public function addIP()
	{
		/** @var Session */
		$sess = Session::getInstance();
		$sess->start();

		if ($sess->skipIPCount || $this->isCrawler()) {
			$sess->skipIPCount = true;

			return;
		}

		/** @var File */
		$dataFile = File::instance(IPCounter::DATAFILE);

		$today = date("ymd");
		$countData = array();

		if ($dataFile->exists()) {
			$json = $dataFile->content();
			$countData = (array) json_decode($json, true);

			if ($countData === null) {
				$countData = array();
				$countData['count'] = 0;
				$countData['days'][$today] = 0;
			}
		} else {
			$countData['count'] = 0;
			$countData['days'][$today] = 0;
		}

		$countData['count']++;
		$countData['days'][$today] = @($countData['days'][$today] + 1) ?: 1;

		$dataFile->save(json_encode($countData));

		$sess->skipIPCount = true;
	}

	public function getStats(): array
	{
		/** @var File */
		$dataFile = File::instance(IPCounter::DATAFILE);

		if ($dataFile->exists()) {
			return json_decode($dataFile->content(), true);
		}

		return ['count' => 0];
	}

	private function isCrawler(): bool
	{
		// basic crawler detection script (no legit browser should match this)
		if (!empty($_SERVER['HTTP_USER_AGENT']) and preg_match('~(bot|crawl)~i', $_SERVER['HTTP_USER_AGENT'])) {
			// this is a crawler and counter should not be updated	-> do nothing
			return true;
		}

		$crawlerDetect = new CrawlerDetect();	// improved crawler detection

		// Check the user agent of the current 'visitor'
		if ($crawlerDetect->isCrawler()) {
			// true if crawler user agent detected
			return true;
		}

		return false;
	}
}
