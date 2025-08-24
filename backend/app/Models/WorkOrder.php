<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'supplier_id',
        'assigned_to',
        'title',
        'description',
        'priority',
        'status',
        'due_date',
        'started_at',
        'completed_at',
        'estimated_cost',
        'actual_cost',
        'location',
        'category',
        'invoice_path',
        'notes'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', Carbon::now())
                    ->whereNotIn('status', ['completed', 'cancelled']);
    }

    // Methods
    public function isOverdue()
    {
        return $this->due_date < Carbon::now() && !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getDurationInHours()
    {
        if (!$this->started_at || !$this->completed_at) {
            return null;
        }

        return Carbon::parse($this->started_at)->diffInHours(Carbon::parse($this->completed_at));
    }

    public function markAsStarted()
    {
        $this->status = 'in_progress';
        $this->started_at = Carbon::now();
        $this->save();
    }

    public function markAsCompleted($invoicePath = null, $actualCost = null)
    {
        $this->status = 'completed';
        $this->completed_at = Carbon::now();
        
        if ($invoicePath) {
            $this->invoice_path = $invoicePath;
        }
        
        if ($actualCost) {
            $this->actual_cost = $actualCost;
        }
        
        $this->save();

        // Create notification for completion
        Notification::createTaskCompleted($this->user_id, $this);
    }

    public function getPriorityColor()
    {
        switch ($this->priority) {
            case 'urgent':
                return 'danger';
            case 'high':
                return 'warning';
            case 'normal':
                return 'info';
            case 'low':
                return 'secondary';
            default:
                return 'info';
        }
    }

    public function getStatusColor()
    {
        switch ($this->status) {
            case 'pending':
                return 'warning';
            case 'in_progress':
                return 'info';
            case 'completed':
                return 'success';
            case 'cancelled':
                return 'secondary';
            default:
                return 'secondary';
        }
    }
}
