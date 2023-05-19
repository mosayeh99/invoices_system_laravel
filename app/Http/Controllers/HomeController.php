<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $invoices_total = number_format(Invoice::sum('total'), 2);
        $invoices_count = Invoice::count();

        $paid_invoices_sum = number_format(Invoice::where('status', 'Paid')->sum('total'), 2);
        $paid_invoices_count = Invoice::where('status', 'Paid')->count();

        $partialPaid_invoices_sum = number_format(Invoice::where('status', 'Partially Paid')->sum('total'), 2);
        $partialPaid_invoices_count = Invoice::where('status', 'Partially Paid')->count();

        $unpaid_invoices_sum = number_format(Invoice::where('status', 'Not Paid')->sum('total'), 2);
        $unpaid_invoices_count = Invoice::where('status', 'Not Paid')->count();

        $paid_invoices_rate = $partialPaid_invoices_rate = $unpaid_invoices_rate = 0;
        if ($invoices_count > 0) {
            $paid_invoices_rate = round(($paid_invoices_count / $invoices_count) * 100, 2);
            $partialPaid_invoices_rate = round(($partialPaid_invoices_count / $invoices_count) * 100, 2);
            $unpaid_invoices_rate = round(($unpaid_invoices_count / $invoices_count) * 100, 2);
        }

        $departments = Department::all();
        $departments_count = $departments->count();
        $data = $labels = $color = [];
        foreach ($departments as $department){
            $data[] = Invoice::where('department_id', $department->id)->count();
            $labels[] = $department->name;
            $color[] = '#'.substr(md5(mt_rand()), 0, 6);
        }

        $invoicesChart = app()->chartjs
            ->name('barChartTest')
            ->type('bar')
            ->size(['width' => 400, 'height' => 200])
            ->labels(['Paid', 'Partially Paid', 'Unpaid'])
            ->datasets([
                [
                    "label" => "Present(%)",
                    'backgroundColor' => ['rgba(65, 207, 161)','rgba(240, 158, 88)','rgba(255, 99, 132)'],
                    'data' => [$paid_invoices_rate, $partialPaid_invoices_rate, $unpaid_invoices_rate]
                ],
            ])
            ->optionsRaw([
                'responsive' => true,
                'legend' => [
                    'display' => false,
                ],
                'scales' => [
                    'yAxes' => [
                        [
                            'ticks' => [
                                'min' => 0,
                                'max' => 100
                            ]
                        ]
                    ],
                ]
            ]);

        $departmentsChart = app()->chartjs
            ->name('pieChartTest')
            ->type('pie')
            ->size(['width' => 400, 'height' => 250])
            ->labels($labels)
            ->datasets([
                [
                    'backgroundColor' => $color,
                    'hoverBackgroundColor' => $color,
                    'data' => $data
                ]
            ])
            ->options([
                'legend' => [
                    'display' => false,
                ],
            ]);

        return view('home', compact(
            'invoices_total',
            'invoices_count',
            'paid_invoices_sum',
            'paid_invoices_count',
            'paid_invoices_rate',
            'unpaid_invoices_sum',
            'unpaid_invoices_count',
            'unpaid_invoices_rate',
            'partialPaid_invoices_sum',
            'partialPaid_invoices_count',
            'partialPaid_invoices_rate',
            'invoicesChart',
            'departmentsChart',
            'departments_count'
        ));
    }
}
