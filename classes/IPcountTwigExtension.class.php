<?php
namespace Grav\Plugin;
use Grav\Common\Plugin;
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
		$file = File::instance(DATA_DIR . 'counter/counter.txt');
		$counter = (int) $file->load();
		//	$counter = 1234567;
		return $counter;
	}
}
?>
