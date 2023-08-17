<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;

trait AutoFocus
{
    /**
     * @return bool
     */
    public static function getAutoFocusOnFirstField()
    {
        return self::getOperationSetting('autoFocusOnFirstField');
    }

    public static function setAutoFocusOnFirstField($value)
    {
        return self::setOperationSetting('autoFocusOnFirstField', (bool) $value);
    }

    public static function enableAutoFocus()
    {
        return self::setAutoFocusOnFirstField(true);
    }

    public static function disableAutoFocus()
    {
        return self::setAutoFocusOnFirstField(false);
    }
}
