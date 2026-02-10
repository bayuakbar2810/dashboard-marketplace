<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    // INI KUNCI MASALAHNYA!
    // Tambahkan baris ini agar data import tidak ditolak
    protected $guarded = [];
}