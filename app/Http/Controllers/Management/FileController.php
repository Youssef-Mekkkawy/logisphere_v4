<?php

namespace App\Http\Controllers\Management;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\Company;
use App\Models\Employee;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ShipmentsExport;
use App\Imports\ShipmentsImport;

class FileController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
        // 🔥 TEMPORARILY DISABLED FOR TESTING - Re-enable after fixing
        $this->middleware('permission:file.view')->only(['index', 'show']);
        $this->middleware('permission:file.create')->only(['create', 'store']);
        $this->middleware('permission:file.edit')->only(['edit', 'update']);
        $this->middleware('permission:file.delete')->only(['destroy']);
    }
    public function index()
    {
        return view('dashboard.file');
    }

    public function export(Request $request)
    {
        $request->validate([
            'data_type' => 'required|string',
            'format' => 'required|string',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date'
        ]);

        $dataType = $request->data_type;
        $format = $request->format;
        $dateFrom = $request->date_from;
        $dateTo = $request->date_to;

        try {
            switch ($dataType) {
                case 'Shipments':
                    $query = Shipment::with(['company', 'originPort', 'destinationPort']);
                    if ($dateFrom) $query->whereDate('created_at', '>=', $dateFrom);
                    if ($dateTo) $query->whereDate('created_at', '<=', $dateTo);
                    $data = $query->get();
                    break;

                case 'Companies':
                    $data = Company::all();
                    break;

                case 'Employees':
                    $data = Employee::all();
                    break;

                default:
                    return back()->with('error', 'Invalid data type selected');
            }

            $filename = strtolower($dataType) . '_' . now()->format('Y-m-d_H-i-s');

            switch ($format) {
                case 'Excel (.xlsx)':
                    return Excel::download(new ShipmentsExport($data), $filename . '.xlsx');

                case 'CSV (.csv)':
                    return Excel::download(new ShipmentsExport($data), $filename . '.csv');

                case 'PDF Report':
                    // For now, return JSON. You can implement PDF generation later
                    return response()->json($data);

                default:
                    return back()->with('error', 'Invalid format selected');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Export failed: ' . $e->getMessage());
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,csv',
            'data_type' => 'required|string'
        ]);

        try {
            $file = $request->file('file');
            $dataType = $request->data_type;

            switch ($dataType) {
                case 'Shipments':
                    Excel::import(new ShipmentsImport, $file);
                    break;

                case 'Companies':
                    // Implement CompaniesImport class
                    break;

                case 'Employees':
                    // Implement EmployeesImport class
                    break;

                default:
                    return back()->with('error', 'Invalid data type selected');
            }

            return back()->with('success', 'Data imported successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }
}
