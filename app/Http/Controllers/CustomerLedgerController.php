<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerLedger;
use Illuminate\Support\Facades\Auth;

class CustomerLedgerController extends Controller
{
    public function index(Customer $customer) {
        $outstanding = CustomerLedger::where('customer_id', $customer->id)
            ->selectRaw("
                SUM(
                    CASE 
                        WHEN type = 'credit' THEN amount 
                        WHEN type = 'debit' THEN -amount 
                        ELSE 0 
                    END
                ) as balance
            ")
            ->value('balance');
        return view('admin.customers.ledgers.index', compact('customer', 'outstanding'));
    }

    public function list(Customer $customer, Request $request) {
        $query = CustomerLedger::where('customer_id', $customer->id)
            ->orderBy('date', 'desc');

        $records = $query->get();

        $data = $records->map(function ($row) {
            $debit = ($row->type == 'debit')?$row->amount:'';
            $credit = ($row->type == 'credit')?$row->amount:'';
            return [
                'date' => \Carbon\Carbon::parse($row->date)->format('d M, Y'),
                'description' => e($row->description),
                'debit' => '<span class="badge bg-danger">'.$debit.'</span>',
                'credit' => '<span class="badge bg-success">'.$credit.'</span>',
                'actions' => view('admin.customers.ledgers.partials.actions', compact('row'))->render(),
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function save(Customer $customer, Request $request) {
        $request->validate([
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'date' => 'required|date',
        ]);

        $ledger = CustomerLedger::updateOrCreate(
            ['id' => $request->id],
            [
                'customer_id' => $customer->id,
                'type' => $request->type,
                'amount' => $request->amount,
                'description' => $request->description,
                'date' => $request->date,
                'updated_by' => Auth::id(),
                'created_by' => Auth::id(),
            ]
        );

        return response()->json(['success' => true, 'ledger' => $ledger]);
    }

    public function destroy($customerId, $ledgerId) {
        $ledger = CustomerLedger::find($ledgerId);
        $ledger->delete();
        return response()->json(['success' => true]);
    }
}
