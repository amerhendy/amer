<?php

namespace Amerhendy\Amer\App\Models\Traits;
use Amerhendy\Amer\App\Helpers\Library\AmerPanel\AmerPanelFacade as Amer;
use Amerhendy\Amer\App\Helpers\Library\Database\TableSchema;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Methods for working with relationships inside select/relationship fields.
|--------------------------------------------------------------------------
*/
trait CustomSchemaMapper
{
    protected string|null $databaseDriver = null;

    public function getConnectionWithExtraTypeMappings(): \Illuminate\Database\Connection
    {
        $connection = DB::connection();
        $this->databaseDriver = $connection->getDriverName();
        $cols=['enum','jsonb','uuid'];
        foreach ($cols as $key => $value) {
            $this->mapDatabaseTypeToLaravel($value);
        }
        return $connection;
    }

    public function mapDatabaseTypeToLaravel(string $type): string
    {
        $type = strtolower($type);

        $typeMappings = [
            'pgsql' => [
                'enum' => 'string',
                'jsonb' => 'json',
                'uuid' => 'string',
            ],
            'mysql' => [
                'enum' => 'string',
                'json' => 'json',
            ],
        ];
        $driver = $this->databaseDriver ?? DB::getDriverName();

        return $typeMappings[$driver][$type] ?? $type;
    }

}
