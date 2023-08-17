<?php
namespace Amerhendy\Amer\App\Helpers\Library\AmerPanel\Traits;
use Amerhendy\Amer\App\Helpers\Library\Exceptions\AccessDeniedException;

trait Access
{
    public function allowAccess($operation)
    {
        foreach ((array) $operation as $op) {
            $this->set($op.'.access', true);
        }

        return $this->hasAccessToAll($operation);
    }
    public function denyAccess($operation)
    {
        foreach ((array) $operation as $op) {
            $this->set($op.'.access', false);
        }

        return ! $this->hasAccessToAny($operation);
    }
    public function hasAccess($operation)
    {
        return $this->get($operation.'.access') ?? false;
    }
    public function hasAccessToAny($operation_array)
    {
        foreach ((array) $operation_array as $key => $operation) {
            if ($this->get($operation.'.access') == true) {
                return true;
            }
        }

        return false;
    }
    public function hasAccessToAll($operation_array)
    {
        foreach ((array) $operation_array as $key => $operation) {
            if (! $this->get($operation.'.access')) {
                return false;
            }
        }

        return true;
    }
    public function hasAccessOrFail($operation)
    {
        if (! $this->get($operation.'.access')) {
            throw new AccessDeniedException(trans('AMER::errors.unauthorized_access', ['access' => $operation]));
        }

        return true;
    }
}
