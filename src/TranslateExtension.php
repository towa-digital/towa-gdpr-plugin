<?php
namespace Towa\DsgvoPlugin;

class TranslateExtension extends \Twig\Extension\AbstractExtension {
    public function getFunctions(){
        return array(
            '__' => new \Twig\TwigFunction('__', [$this, 'translate'])
        );
    }

    public function getName(){
        return 'TranslateExtension';
    }

    public function translate($string, $handle){
        return __($string, $handle);
    }
}
