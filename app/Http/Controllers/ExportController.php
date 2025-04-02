<?php

namespace App\Http\Controllers;

use PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use App\Models\Sale;
use App\Models\User;
use App\Models\Purchase;
use App\Models\SaleDetail;
use App\Exports\SalesExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\inventory\export\ProductsExport;


class ExportController extends Controller
{
    public function reportPDF($userId, $reportType, $dateFrom = null, $dateTo = null)
    {
        // dd($dateFrom, $dateTo);
        $data = [];

        if ($reportType == 0) // ventas del dia
        {
            $from = Carbon::parse(Carbon::now())->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse(Carbon::now())->format('Y-m-d')   . ' 23:59:59';
        } else {
            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($dateTo)->format('Y-m-d')     . ' 23:59:59';
        }


        if ($userId == 0) {
            $data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', 'u.name as usuario')
                ->whereBetween('sales.created_at', [$from, $to])
                ->get();
        } else {
            $data = Sale::join('users as u', 'u.id', 'sales.user_id')
                ->select('sales.*', 'u.name as usuario')
                ->whereBetween('sales.created_at', [$from, $to])
                ->where('user_id', $userId)
                ->get();
        }

        if ($userId == 0) {
            $user = 'Todos';
        } else {
            $u = User::find($userId);
            $user = $u->name . '' . $u->last_name;
        }

        //$last_name =$userId == 0? '': User::find($userId)->last_name; // 0

        $pdf = PDF::loadView('pdf.reporte', compact('data', 'user',  'reportType', 'dateFrom', 'dateTo'));

        /*
    $pdf = new DOMPDF();
    $pdf->setBasePath(realpath(APPLICATION_PATH . '/css/'));
    $pdf->loadHtml($html);
    $pdf->render();
    */
        /*
    $pdf->set_protocol(WWW_ROOT);
    $pdf->set_base_path('/');
*/

        return $pdf->stream('salesReport.pdf'); // visualizar
        //$customReportName = 'salesReport_'.Carbon::now()->format('Y-m-d').'.pdf';
        //return $pdf->download($customReportName); //descargar

    }

    public function exportProducts()
    {
        $date = Carbon::now()->format('d-m-Y_H-i-s');
        $reportName = 'Reporte_de_Inventario_' . $date . '.xlsx';
        return Excel::download(new ProductsExport, $reportName);
    }


    public function reporteExcel($userId, $reportType, $dateFrom = null, $dateTo = null)
    {
        $reportName = 'Reporte de Ventas_' . uniqid() . '.xlsx';

        return Excel::download(new SalesExport($userId, $reportType, $dateFrom, $dateTo), $reportName);
    }



    public function reportPurchasePDF($userId, $reportType, $dateFrom = null, $dateTo = null)
    {

        $data = [];

        if ($reportType == 0) // compras del dia
        {
            $from = Carbon::parse(Carbon::now())->startOfDay();
            $to = Carbon::parse(Carbon::now())->endOfDay();
        } else {
            $from = Carbon::parse($dateFrom)->format('Y-m-d') . ' 00:00:00';
            $to = Carbon::parse($dateTo)->format('Y-m-d')     . ' 23:59:59';
        }



        if ($reportType == 1 && ($dateFrom == '' || $dateTo == '')) {
            $data = [];
            return;
        }

        if ($userId == 0) {
            $data = Purchase::join('users as u', 'u.id', 'purchases.user_id')
                ->select('purchases.*', 'u.name as usuario')
                ->whereBetween('purchases.created_at', [$from, $to])
                ->get();
        } else {
            $data = Purchase::join('users as u', 'u.id', 'purchases.user_id')
                ->select('purchases.id', 'purchases.total', 'purchases.items', 'purchases.status', 'u.name as usuario', 'purchases.created_at')
                ->whereBetween('purchases.created_at', [$from, $to])
                ->where('user_id', $userId)
                ->get();
        }


        if ($userId == 0) {
            $user = 'Todos';
        } else {
            $u = User::find($userId);
            $user = $u->name . '' . $u->last_name;
        }



        $pdf = PDF::loadView('pdf.purchases-report', compact('data', 'user',  'reportType', 'dateFrom', 'dateTo'));


        return $pdf->stream('purchasesReport.pdf'); // visualizar


    }
}
