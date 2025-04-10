<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prodi extends Model
{
    use HasFactory;
    protected $table = 'prodi';

    protected $fillable = ['name'];
    public function fakultas()
    {
        return $this->belongsTo(Fakultas::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
}