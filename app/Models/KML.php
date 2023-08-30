<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KML extends Model
{
    public $table = 'k_m_l_s';

    public $fillable = [
        'kml_file'
    ];

    protected $casts = [
        'kml_file' => 'string'
    ];

    public static array $rules = [
        'kml_file' => 'required'
    ];


}
