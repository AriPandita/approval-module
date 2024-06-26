<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    use HasFactory;

    protected $table = 'approval';

    protected $fillable = [
        'data_id', 
        'user_id_start', 
        'user_id_approver', 
        'module', 'sub_modul', 
        'action', 
        'information', 
        'data', 
        'status',
        'date_process',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected $casts = [
        'data' => 'array',
    ];
}
