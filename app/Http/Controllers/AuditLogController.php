<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                  ->orWhere('aksi', 'like', '%' . $search . '%')
                  ->orWhere('tabel', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $logs = $query->paginate(20);
        return view('audit_log.index', compact('logs'));
    }

    public function exportPdf(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', '%' . $search . '%')
                  ->orWhere('aksi', 'like', '%' . $search . '%')
                  ->orWhere('tabel', 'like', '%' . $search . '%')
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nama', 'like', '%' . $search . '%');
                  });
            });
        }

        $logs = $query->get();
        
        $pdf = Pdf::loadView('audit_log.pdf', compact('logs', 'request'));
        
        $pdf->setPaper('a4', 'landscape'); 

        return $pdf->download('Laporan_Audit_Log_' . date('Ymd_His') . '.pdf');
    }
}