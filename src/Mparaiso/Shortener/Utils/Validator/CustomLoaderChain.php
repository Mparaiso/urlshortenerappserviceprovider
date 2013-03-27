<?php
namespace Mparaiso\Utils\Validator;

use Symfony\Component\Validator\Mapping\Loader\LoaderChain;
use Symfony\Component\Validator\Mapping\Loader\LoaderInterface;

class CustomLoaderChain extends LoaderChain
{

    function getLoaders()
    {
        return $this->loaders;
    }

    function addLoader(LoaderInterface $loader)
    {
        $this->loaders[] = $loader;
    }

    function removeLoader(LoaderInterface $loader)
    {
        for ($i = 0; $i < count($this->loaders); $i++) {
            if ($this->loaders[$i] === $loader) {
                array_splice($this->loaders, $i, 1);
            }
        }
    }

}