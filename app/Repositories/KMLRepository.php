<?php

namespace App\Repositories;

use App\Models\KML;
use App\Repositories\BaseRepository;

class KMLRepository extends BaseRepository
{
    protected $fieldSearchable = [
        'kml_file'
    ];

    public function getFieldsSearchable(): array
    {
        return $this->fieldSearchable;
    }

    public function model(): string
    {
        return KML::class;
    }
}
