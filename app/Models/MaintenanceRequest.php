<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaintenanceRequest extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * Ensure this matches your migration name.
     * @var string
     */
    protected $table = 'maintenance_requests';

    /**
     * The attributes that are mass assignable.
     * These must match the columns you defined in your migration.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'status',
        'priority',
    ];

    /**
     * Define the relationship: A request belongs to a User.
     * This is used for eager loading (e.g., in the MaintenanceRequestController::index).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}