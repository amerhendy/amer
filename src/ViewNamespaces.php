<?php

namespace Amerhendy\Amer;

class ViewNamespaces
{
    private static $viewNamespaces = [];

    /**
     * Return all the view namespaces including the ones stored in the laravel config files.
     *
     * @param  string  $domain  (eg. fields, filters, buttons)
     * @return array
     */
    public static function allnamespaces($domain,$customConfigKey = null){
        $domains=[
            'columns'=>Baseview('Base.page.main.List.columns'),
            'buttons'=>Baseview('Base.page.main.List.buttons'),
        ];
        if(array_key_exists($domain,$domains)){
            return [$domains[$domain]];
        }else{
            return config($customConfigKey ?? 'amer.view_namespace.'.$domain) ?? [];
        }
    }
    public static function getFor(string $domain)
    {
        $viewNamespacesFromConfig = self::getFromConfigFor($domain);

        return array_unique(array_merge($viewNamespacesFromConfig, self::getForDomain($domain)));
    }

    /**
     * Add view namespaces for a given domain.
     *
     * @param  string  $domain  (eg. fields, filters, buttons)
     * @param  string|array  $viewNamespaces
     * @return void
     */
    public static function addFor(string $domain, $viewNamespaces)
    {
        foreach ((array) $viewNamespaces as $viewNamespace) {
            if (! in_array($viewNamespace, self::getForDomain($domain))) {
                self::$viewNamespaces[$domain][] = $viewNamespace;
            }
        }
    }

    /**
     * Return the namespaces stored for a given domain.
     *
     * @param  string  $domain
     */
    private static function getForDomain(string $domain)
    {
        return self::$viewNamespaces[$domain] ?? [];
    }
    private static function getFromConfigFor(string $domain, $customConfigKey = null)
    {
        return self::allnamespaces($domain);
        
    }

    /**
     * Return all the view namespaces using a developer provided config key.
     * Allow developer to use view namespaces from other config keys.
     *
     * @param  string  $domain  (eg. fields, filters, buttons)
     * @param  string  $viewNamespacesFromConfigKey
     * @return array
     */
    public static function getWithFallbackFor(string $domain, string $viewNamespacesFromConfigKey)
    {
        $viewNamespacesFromConfig = self::getFromConfigFor($domain, $viewNamespacesFromConfigKey);

        return array_unique(array_merge($viewNamespacesFromConfig, self::getFor($domain)));
    }

    /**
     * This is an helper function that returns the view namespace with the view name appended.
     * It's usefull to use in blade templates with `@includeFirst(ViewNamespaces::getViewPathsFor('columns', 'some_column'))`.
     *
     * @param  string  $domain
     * @param  string  $viewName
     * @return array
     */
    public static function getViewPathsFor(string $domain, string $viewName)
    {
        return array_map(function ($item) use ($viewName) {
            return $item.'.'.$viewName;
        }, self::getFor($domain));
    }
    
}
