<?php

// File.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    // Corrigir para 'path' (o nome da coluna na migração)
    protected $fillable = ['user_id', 'filename', 'path', 'mime_type'];

    // Relacionamento: cada arquivo pertence a um usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}