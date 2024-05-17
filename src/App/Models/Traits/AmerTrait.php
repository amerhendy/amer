<?php

namespace Amerhendy\Amer\App\Models\Traits;

trait AmerTrait
{
    use HasIdentifiableAttribute;
    use HasEnumFields;
    use HasRelationshipFields;
    use HasUploadFields;
    use HasFakeFields;
    use HasTranslatableFields;

    public static function hasAmerTrait()
    {
        return true;
    }
}
