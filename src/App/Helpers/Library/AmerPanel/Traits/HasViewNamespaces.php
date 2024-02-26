<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

use Amerhendy\Amer\ViewNamespaces;

/**
 * @codeCoverageIgnore
 */
trait HasViewNamespaces
{
    /**
     * This file is only needed because we messed up version constrains from
     * 1.2 up to 1.2.6 of PRO version and any user that the license ended
     * in the middle of those versions was not able to update

     *
     * This should be removed in the next major version.
     */
    public static function addViewNamespacesFor(string $domain, array $viewNamespaces)
    {
        ViewNamespaces::addFor($domain, $viewNamespaces);
    }

    public static function addViewNamespaceFor(string $domain, string $viewNamespace)
    {
        ViewNamespaces::addFor($domain, $viewNamespace);
    }

    public static function getViewNamespacesFor(string $domain)
    {
        ViewNamespaces::getFor($domain);
    }

    public static function getViewNamespacesWithFallbackFor(string $domain, string $viewNamespacesFromConfigKey)
    {
        ViewNamespaces::getWithFallbackFor($domain, $viewNamespacesFromConfigKey);
    }
}
