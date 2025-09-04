<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DonationsExport;

class ReportController extends Controller
{
    public function index()
    {
        return view('admin.reports.index');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'report_type' => 'required|in:donations,donors,pledges',
            'format' => 'required|in:pdf,csv,excel'
        ]);

        $query = $this->buildQuery($request);
        $data = $query->get();

        switch ($request->format) {
            case 'pdf':
                return $this->generatePDF($data, $request);
            case 'csv':
                return $this->generateCSV($data, $request);
            case 'excel':
                return $this->generateExcel($data, $request);
        }
    }

    private function buildQuery(Request $request)
    {
        switch ($request->report_type) {
            case 'donations':
                return Donation::with(['user'])
                    ->whereBetween('created_at', [$request->date_from, $request->date_to])
                    ->when($request->status, function($q) use ($request) {
                        return $q->where('status', $request->status);
                    })
                    ->when($request->donor_type, function($q) use ($request) {
                        return $q->where('donor_type', $request->donor_type);
                    });
                    
            case 'donors':
                return User::where('role', 'donor')
                    ->whereBetween('created_at', [$request->date_from, $request->date_to])
                    ->when($request->donor_type, function($q) use ($request) {
                        return $q->where('donor_type', $request->donor_type);
                    });
                    
            default:
                return collect();
        }
    }

    private function generatePDF($data, Request $request)
    {
        $pdf = PDF::loadView('admin.reports.pdf', [
            'data' => $data,
            'request' => $request,
            'generated_at' => now()
        ]);

        $filename = $request->report_type . '_report_' . date('Y-m-d') . '.pdf';
        return $pdf->download($filename);
    }

    private function generateCSV($data, Request $request)
    {
        return Excel::download(new DonationsExport($data), 
            $request->report_type . '_report_' . date('Y-m-d') . '.csv');
    }

    private function generateExcel($data, Request $request)
    {
        return Excel::download(new DonationsExport($data), 
            $request->report_type . '_report_' . date('Y-m-d') . '.xlsx');
    }
}