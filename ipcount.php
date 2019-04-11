<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class IPcountPlugin
 * @package Grav\Plugin
 */
class IPcountPlugin extends Plugin
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
	
	public function onTwigExtensions()
    {
        require_once(__DIR__ . '/classes/IPcountTwigExtension.class.php');
        $this->grav['twig']->twig->addExtension(new IPcountTwigExtension());	// war zuerst in onPluginsInitialized, s.o.
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
		require_once __DIR__ . '/classes/ipcount.class.php';
		
        global $_SERVER;
		if (!isset($ip)) {
				$ip = $_SERVER['REMOTE_ADDR'];
		}
		$countplugin = new ipCount();
		$countplugin->count($this->grav['cache'], $ip);	// wird gebraucht, sonst kein count :)
    }
}
