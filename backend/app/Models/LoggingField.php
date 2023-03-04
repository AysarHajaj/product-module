<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoggingField extends Model
{
    use HasFactory;

    protected $table = 'logging_fields';
    protected $fillable = [
        'logging_id',
        'field_name',
        'field_old_value',
        'field_new_value',
    ];

    public function logging()
    {
        return $this->belongsTo(Logging::class, 'logging_id');
    }
}
