<?php

namespace App\Models;

use App\Concerns\InteractsWithAuditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class OrganizationInvitation extends Model
{
    use HasFactory;
    use InteractsWithAuditable;
    use Notifiable;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
