<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Donation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'donor_name', 'donor_email', 'donor_phone', 'is_anonymous',
        'amount', 'payment_method', 'payment_screenshot', 'payment_details',
        'status', 'donor_type', 'admin_notes', 'approved_at', 'approved_by'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_anonymous' => 'boolean',
            'approved_at' => 'datetime',
            'payment_details' => 'array',
        ];
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeByDateRange($query, $dateFrom, $dateTo)
    {
        return $query->whereBetween('created_at', [$dateFrom, $dateTo]);
    }

    public function scopeByDonorName($query, $name)
    {
        return $query->where('donor_name', 'LIKE', "%{$name}%");
    }
}