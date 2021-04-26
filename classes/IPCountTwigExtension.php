<?php
namespace Grav\Plugin\IPCount;

class IPCountTwigExtension extends \Twig_Extension
{
	public function getName()
	{
		return 'IPCountTwigExtension';
	}

	public function getFunctions()
	{
		return [
			new \Twig_SimpleFunction('counter', [$this, 'countFunction'])
		];
	}

	public function countFunction(): int
	{
		$ipcount = new IPCounter();
		$stats = $ipcount->getStats();

		return $stats['count'];
	}
}
?>
