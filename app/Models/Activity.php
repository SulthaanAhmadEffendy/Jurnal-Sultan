<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model

{
    protected $table = 'activities';
    protected $fillable = [
        'jurnal_entry_id',
        'timing',
        'category',
        'supervisor_instruction',
        'description',
        'target_completion',
        'status',
        'reason_not_achieved',
    ];

    public function jurnalEntry()
    {
        return $this->belongsTo(JurnalEntry::class);
    }
}
