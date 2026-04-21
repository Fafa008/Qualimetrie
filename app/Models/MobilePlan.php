<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilePlan extends Model
{
    protected $fillable = [
        'dataUsed',
        'dataNonEU_MB',
        'anciennete',
        'isEtudiant',
        'total'
    ];
}
