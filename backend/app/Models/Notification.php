<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'read_at',
        'priority',
        'source_id',
        'source_type'
    ];

    protected $casts = [
        'data' => 'json',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    // Scopes
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Methods
    public function markAsRead()
    {
        $this->read_at = Carbon::now();
        $this->save();
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function isUrgent()
    {
        return $this->priority === 'urgent';
    }

    public function getTimeAgo()
    {
        return $this->created_at->diffForHumans();
    }

    // Static methods for creating notifications
    public static function createLabRequest($labTechId, $labOrder)
    {
        // Handle both array and object data
        $testType = is_array($labOrder) ? $labOrder['test_type'] : $labOrder->test_type;
        $patientName = is_array($labOrder) ? $labOrder['patient_name'] : ($labOrder->patient->name ?? 'Unknown');
        $priority = is_array($labOrder) ? ($labOrder['priority'] ?? 'routine') : ($labOrder->priority ?? 'routine');
        $doctorName = is_array($labOrder) ? ($labOrder['doctor_name'] ?? 'Unknown') : ($labOrder->doctor->name ?? 'Unknown');
        
        return self::create([
            'user_id' => $labTechId,
            'type' => 'lab_request',
            'title' => 'New Lab Test Requested',
            'message' => "New {$testType} requested for patient {$patientName}",
            'data' => [
                'patient_name' => $patientName,
                'test_type' => $testType,
                'priority' => $priority,
                'doctor_name' => $doctorName,
                'requested_at' => Carbon::now()->toISOString()
            ],
            'priority' => $priority,
            'source_id' => is_array($labOrder) ? null : $labOrder->id,
            'source_type' => 'lab_order'
        ]);
    }

    public static function createImagingRequest($radiologistId, $imagingStudy)
    {
        return self::create([
            'user_id' => $radiologistId,
            'type' => 'imaging_request',
            'title' => 'New Imaging Study Requested',
            'message' => "New {$imagingStudy->study_type} requested for patient {$imagingStudy->patient->name}",
            'data' => [
                'patient_name' => $imagingStudy->patient->name,
                'study_type' => $imagingStudy->study_type,
                'priority' => $imagingStudy->priority ?? 'routine',
                'doctor_name' => $imagingStudy->doctor->name ?? 'Unknown',
                'requested_at' => Carbon::now()->toISOString()
            ],
            'priority' => $imagingStudy->priority ?? 'routine',
            'source_id' => $imagingStudy->id,
            'source_type' => 'imaging_study'
        ]);
    }

    public static function createWorkOrder($userId, $workOrder)
    {
        $title = is_object($workOrder) && isset($workOrder->title) ? $workOrder->title : 'Work Order';
        $description = is_object($workOrder) && isset($workOrder->description) ? $workOrder->description : 'New work order assigned';
        $priority = is_object($workOrder) && isset($workOrder->priority) ? $workOrder->priority : 'normal';
        $dueDate = is_object($workOrder) && isset($workOrder->due_date) ? $workOrder->due_date : null;
        $workOrderId = is_object($workOrder) && isset($workOrder->id) ? $workOrder->id : null;
        
        return self::create([
            'user_id' => $userId,
            'type' => 'work_order',
            'title' => "Work Order #{$workOrderId} Assigned",
            'message' => "Work order for {$title} has been assigned to you",
            'data' => [
                'work_order_id' => $workOrderId,
                'title' => $title,
                'description' => $description,
                'priority' => $priority,
                'due_date' => $dueDate,
                'assigned_at' => Carbon::now()->toISOString()
            ],
            'priority' => $priority,
            'source_id' => $workOrderId,
            'source_type' => 'work_order'
        ]);
    }

    public static function createTaskCompleted($userId, $task)
    {
        return self::create([
            'user_id' => $userId,
            'type' => 'task_completed',
            'title' => 'Task Completed',
            'message' => "Task has been marked as completed",
            'data' => [
                'task_type' => $task->type ?? 'general',
                'completed_at' => Carbon::now()->toISOString(),
                'duration' => $task->duration ?? null
            ],
            'priority' => 'normal',
            'source_id' => $task->id,
            'source_type' => get_class($task)
        ]);
    }
}
