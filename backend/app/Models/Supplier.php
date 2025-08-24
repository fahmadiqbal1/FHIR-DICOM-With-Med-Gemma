<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'contact_person',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'tax_id',
        'payment_terms',
        'credit_limit',
        'is_active',
        'status',
        'category',
        'notes'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'credit_limit' => 'decimal:2'
    ];

    // Relationships
    public function workOrders()
    {
        return $this->hasMany(WorkOrder::class);
    }

    public function supplierAssignments()
    {
        return $this->hasMany(SupplierAssignment::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Methods
    public function getTotalOrders()
    {
        return $this->workOrders()->count();
    }

    public function getPendingOrders()
    {
        return $this->workOrders()->where('status', 'pending')->count();
    }

    public function getAverageCompletionTime()
    {
        $completedOrders = $this->workOrders()
            ->where('status', 'completed')
            ->whereNotNull('completed_at')
            ->get();

        if ($completedOrders->isEmpty()) {
            return null;
        }

        $totalHours = 0;
        foreach ($completedOrders as $order) {
            $totalHours += Carbon::parse($order->created_at)->diffInHours(Carbon::parse($order->completed_at));
        }

        return round($totalHours / $completedOrders->count(), 2);
    }
}
