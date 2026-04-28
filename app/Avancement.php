<?php

namespace App;

use Illuminate\Support\Facades\DB;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
use App\Projet;

class Avancement extends Model
{

    protected $fillable = [
        'projet_id',
        'montantAlloue',
        'montantEC',
        'montantPC',
        'delaiR',
        'etatPhysique',
        'tauxA',
        'observation'
    ];

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

    function projet()
    {
        return $this->belongsTo('App\Projet');
    }
}
