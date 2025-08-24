<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    
    public function index(Request $request)
    {
        if (auth()->user()->role->slug == 'customer' && auth()->user()->customer->hide_dashboard == 0) {
            return view('admin.hide-dashboard');
        }

        $customers = Customer::all();
        $users = User::where('role_id', 2)->get();

        $customerActiveDocs = DB::table('customer_documents')
            ->join('customers', 'customers.id', '=', 'customer_documents.customer_id')
            ->where('customers.status', 1)
            ->whereNull('customer_documents.deleted_at')
            ->select('customer_documents.*')
            ->get();

        $customerInActiveDocs = DB::table('customer_documents')
            ->join('customers', 'customers.id', '=', 'customer_documents.customer_id')
            ->where('customers.status', 0)
            ->whereNull('customer_documents.deleted_at')
            ->select('customer_documents.*')
            ->get();

        $customerDeleteDocs = DB::table('customer_documents')
            ->whereNotNull('deleted_at')
            ->get();

        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate   = $request->get('end_date', Carbon::now()->endOfMonth()->toDateString());

        $period = new \DatePeriod(
            new \DateTime($startDate),
            new \DateInterval('P1D'),
            (new \DateTime($endDate))->modify('+1 day')
        );

        $dates = [];
        foreach ($period as $date) {
            $dates[$date->format('Y-m-d')] = 0; 
        }

        $uploads = DB::table('customer_documents') 
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$startDate, $endDate])
            ->groupBy('date')
            ->pluck('total', 'date')
            ->toArray();

        foreach ($uploads as $date => $count) {
            $dates[$date] = $count;
        }

        $documentsPerDay = collect($dates)->map(function ($total, $date) {
            return ['date' => $date, 'total' => $total];
        })->values();

        $activeCount = $customerActiveDocs->count();
        $inactiveCount = $customerInActiveDocs->count();
        $deletedCount = $customerDeleteDocs->count();

        return view('admin.dashboard', compact(
            'customers',
            'users',
            'customerActiveDocs',
            'customerInActiveDocs',
            'customerDeleteDocs',
            'documentsPerDay',
            'startDate',
            'endDate',
            'activeCount',
            'inactiveCount',
            'deletedCount',
        ));
    }
}
