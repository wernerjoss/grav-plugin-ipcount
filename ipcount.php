<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;

use Grav\Plugin\IPCount\IPCounter;
use Grav\Plugin\IPCount\IPCountTwigExtension;

/**
 * Class IPCountPlugin
 * @package Grav\Plugin
 */
class IPCountPlugin extends Plugin
{
	/**
	* @return array
	*
	*/
	public static function getSubscribedEvents()
	{
		return [
			'onPluginsInitialized' => ['onPluginsInitialized', 0],
		];
	}

	/**
	 * Composer autoload.
	 *
	 * @return \Composer\Autoload\ClassLoader
	 */
	public function autoload(): \Composer\Autoload\ClassLoader
	{
		return require __DIR__ . '/vendor/autoload.php';
	}

	/**
	 * Initialize the plugin
	 */
	public function onPluginsInitialized()
	{
		// Don't proceed if we are in the admin plugin
		if ($this->isAdmin()) {
			return;
		}

		$this->enable([
			'onTwigExtensions' => ['onTwigExtensions', 0],
			'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
		]);

		$this->countIP();
	}

	private function countIP()
	{
		$ip = $_SERVER['REMOTE_ADDR'];

		// Exclude localhost address
		if ($ip === null || $ip === '::1') {
			return;
		}

		$ipCounter = new IPCounter();
		$ipCounter->addIP();
	}

	public function onTwigExtensions()
	{
		$this->grav['twig']->twig->addExtension(new IPCountTwigExtension());	// war zuerst in onPluginsInitialized, s.o.
	}


	public function onTwigTemplatePaths()
	{
		$this->grav['twig']->twig_paths[] = 'plugins://' . $this->name . '/templates';
	}
}
