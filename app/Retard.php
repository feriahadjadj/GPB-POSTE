<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;

class Retard extends Model
{
    protected $fillable = [
        'projet_id',
        'type',
        'date_arret',
        'date_reprise',
        'reason',
        'attachment'   // ✅ REQUIRED
    ];

    protected $casts = [
        'date_arret' => 'date:Y-m-d',
        'date_reprise' => 'date:Y-m-d',
    ];

    public function projet()
    {
        return $this->belongsTo('App\Projet');
    }
    
    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function fromDateTime($value)
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d\TH:i:s');
        }

        return $value;
    }
}
