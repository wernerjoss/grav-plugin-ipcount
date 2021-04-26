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
			'onTwigExtensions' => ['onTwigExtensions', 0]		// wichtig, funktioniert sonst nicht !!
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

	public function onTwigExtensions()
	{
		$this->grav['twig']->twig->addExtension(new IPCountTwigExtension());	// war zuerst in onPluginsInitialized, s.o.
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
		global $_SERVER;
		if (!isset($ip)) {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		$countplugin = new IPCounter();
		if ($ip != null) {
			$countplugin->count($this->grav['cache'], $ip);	// wird gebraucht, sonst kein count :)
			//	$this->grav['log']->info("IP:".$ip." UA:".$_SERVER['HTTP_USER_AGENT']." isBot:".$_SESSION['isBot']);
		}
	}
}
