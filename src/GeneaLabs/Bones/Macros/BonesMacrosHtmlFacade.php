<?php namespace GeneaLabs\Bones\Macros;

use Illuminate\Support\Facades\Facade;

/**
 * @see \GeneaLabs\Bones\Macros\BonesMacrosHtmlBuilder
 */
class BonesMacrosHtmlFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'html';
    }
}
