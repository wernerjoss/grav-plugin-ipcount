<?php

namespace Grav\Plugin;

use Grav\Common\Assets;
use Grav\Common\Page\Page;
use Grav\Common\Plugin;
use Grav\Plugin\IPCount\IPCounter;
use RocketTheme\Toolbox\Event\Event;

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
            $this->enable([
                'onGetPageTemplates' => ['onGetPageTemplates', 0],  // this is finally the right place :-) 18.07.23
            ]);
            return;
        }

        $this->enable([
            'onTwigExtensions' => ['onTwigExtensions', 0],
            'onTwigTemplatePaths' => ['onTwigTemplatePaths', 0],
            'onPageInitialized' => ['onPageInitialized', 0],
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

    public function onPageInitialized(Event $event)
    {
        /** @var Page */
        $page = $event['page'];

        if ($page->template() === 'visitors') {
            $this->addAssets();
        }
    }

    private function addAssets()
    {
        /** @var Assets */
        $assets = $this->grav['assets'];

        $assets->addJs('plugins://' . $this->name . '/assets/ipcountplotter.js', ['group' => 'bottom']);
        $assets->addJs('plugins://' . $this->name . '/assets/chart.min.js', ['group' => 'bottom']); // this is chart.js 3.2.1, local copy instead of cdn
        $ipCounter = new IPCounter();
        $stats = $ipCounter->getStats();

        $assets->addInlineJs(
            'const ipcount = ' . json_encode($stats) . ';'
        );
    }

    public function onTwigExtensions()
    {
        $this->grav['twig']->twig->addFunction(
            new \Twig_SimpleFunction('counter', function () {
                $ipcount = new IPCounter();
                $stats = $ipcount->getStats();

                return $stats['count'];
            })
        );
    }

    public function onTwigTemplatePaths()
    {
        $this->grav['twig']->twig_paths[] = 'plugins://' . $this->name . '/templates';
    }

    // register Template 'visitors' so it can be used in the admin backend without having to be copied to the theme's template folder
    // see https://github.com/getgrav/grav-learn/pull/907
    public function onGetPageTemplates(Event $event)
    {
        /** @var Types $types */
        $types = $event->types;
        $types->register('visitors');
        $types->scanTemplates(__DIR__ . '/templates');
    }
}
