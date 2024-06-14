<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JurnalEntry extends Model

{
    protected $table = 'jurnal_entries';
    protected $fillable = ['date'];
    protected $dates = ['date'];

    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }


    public function isEditable()
    {

        return $this->created_at->diffInHours(now()) < 24;
    }
}
