<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DonationsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Donor Name',
            'Donor Email',
            'Donor Phone',
            'Amount',
            'Payment Method',
            'Status',
            'Donor Type',
            'Is Anonymous',
            'Created At',
            'Approved At'
        ];
    }

    public function map($donation): array
    {
        return [
            $donation->id,
            $donation->donor_name,
            $donation->donor_email,
            $donation->donor_phone,
            $donation->amount,
            ucfirst($donation->payment_method),
            ucfirst($donation->status),
            ucfirst($donation->donor_type),
            $donation->is_anonymous ? 'Yes' : 'No',
            $donation->created_at->format('Y-m-d H:i:s'),
            $donation->approved_at ? $donation->approved_at->format('Y-m-d H:i:s') : '-'
        ];
    }
}