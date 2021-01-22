<?php
namespace Grav\Plugin;
use Grav\Common\Plugin;
use Grav\Common\Yaml;
use RocketTheme\Toolbox\File\File;

class IPcountTwigExtension extends \Twig_Extension
{
	public function getName()
	{
		return 'IPcountTwigExtension';
	}
	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('counter', [$this, 'countFunction'])
		];
	}
	public function countFunction()
	{
		//	$file = File::instance(DATA_DIR . 'counter/counter.txt');
		//	$counter = (int) $file->load();	// old txt file approach
		$counter = 0;
		$countdata = array();
		$path = DATA_DIR . 'counter/counter.yaml';	// yaml file from 22.01.21 !
		$countdata = array();
		$yamlfile = File::instance($path);
		$countdata = Yaml::parse($yamlfile->content());
		$counter = (int) $countdata['count'];		
		return $counter;
	}
}
?>
