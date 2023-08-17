<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

trait Tabs
{
    public static function enableTabs()
    {
        self::setOperationSetting('tabsEnabled', true);
        self::setOperationSetting('tabsType', config('Amer.Base'.self::getCurrentOperation().'.tabsType', 'horizontal'));

        return self::tabsEnabled();
    }

    public static function disableTabs()
    {
        self::setOperationSetting('tabsEnabled', false);

        return self::tabsEnabled();
    }

    /**
     * @return bool
     */
    public static function tabsEnabled()
    {
        return self::getOperationSetting('tabsEnabled');
    }

    /**
     * @return bool
     */
    public static function tabsDisabled()
    {
        return ! self::tabsEnabled();
    }

    public static function setTabsType($type)
    {
        self::enableTabs();
        self::setOperationSetting('tabsType', $type);

        return self::getOperationSetting('tabsType');
    }

    /**
     * @return string
     */
    public static function getTabsType()
    {
        return self::getOperationSetting('tabsType');
    }

    public static function enableVerticalTabs()
    {
        return self::setTabsType('vertical');
    }

    public static function disableVerticalTabs()
    {
        return self::setTabsType('horizontal');
    }

    public static function enableHorizontalTabs()
    {
        return self::setTabsType('horizontal');
    }

    public static function disableHorizontalTabs()
    {
        return self::setTabsType('vertical');
    }

    /**
     * @param  string  $label
     * @return bool
     */
    public static function tabExists($label)
    {
        $tabs = self::getTabs();

        return in_array($label, $tabs);
    }

    /**
     * @return bool|string
     */
    public static function getLastTab()
    {
        $tabs = self::getTabs();

        if (count($tabs)) {
            return last($tabs);
        }

        return false;
    }

    /**
     * @param $label
     * @return bool
     */
    public static function isLastTab($label)
    {
        return self::getLastTab() == $label;
    }

    /**
     * @deprecated Do not use this method as it will be removed in future versions!
     * Instead, use self::getElementsWithoutATab(self::getCurrentFields())
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getFieldsWithoutATab()
    {
        return self::getElementsWithoutATab(self::getCurrentFields());
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public static function getElementsWithoutATab(array $elements)
    {
        return collect($elements)->filter(function ($value) {
            return ! isset($value['tab']);
        });
    }

    /**
     * @deprecated Do not use this method as it will be removed in future versions!
     * Instead, use self::getTabItems($tabLabel, 'fields')
     *
     * @return array|\Illuminate\Support\Collection
     */
    public static function getTabFields(string $tabLabel)
    {
        return self::getTabItems($tabLabel, 'fields');
    }

    /**
     * @return array|\Illuminate\Support\Collection
     */
    public static function getTabItems(string $tabLabel, string $source)
    {
        if (in_array($tabLabel, self::getUniqueTabNames($source))) {
            $items = self::getCurrentItems($source);

            return collect($items)->filter(function ($value) use ($tabLabel) {
                return isset($value['tab']) && $value['tab'] == $tabLabel;
            });
        }

        return [];
    }

    public static function getTabs(): array
    {
        return self::getUniqueTabNames('fields');
    }

    /**
     * $source could be `fields` or `columns` for now.
     */
    public static function getUniqueTabNames(string $source): array
    {
        $tabs = [];
        $items = self::getCurrentItems($source);

        collect($items)
            ->filter(function ($value) {
                return isset($value['tab']);
            })
            ->each(function ($value) use (&$tabs) {
                if (! in_array($value['tab'], $tabs)) {
                    $tabs[] = $value['tab'];
                }
            });

        return $tabs;
    }

    private function getCurrentItems(string $source): array
    {
        $items = [];

        switch ($source) {
            case 'fields':
                $items = self::getCurrentFields();
                break;
            case 'columns':
                $items = self::columns();
                break;
            default:
                break;
        }

        return $items;
    }
}
