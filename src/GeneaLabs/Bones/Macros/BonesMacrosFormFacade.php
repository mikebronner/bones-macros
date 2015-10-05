<?php namespace GeneaLabs\Bones\Macros;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GeneaLabs\Bones\Macros\BonesMacrosFormBuilder
 */
class BonesMacrosFormFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'form';
    }
}
