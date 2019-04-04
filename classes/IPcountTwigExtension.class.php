<?php
namespace Grav\Plugin;
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
        return $counter;
        //	return '123456';
    }
}
?>
