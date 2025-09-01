<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerGstYearVerified;

class CustomerGstYearController extends Controller
{
    public function verify(Request $request, $customerId)
    {
        $request->validate([
            'gst_year_id' => 'required|integer|exists:gst_years,id',
        ]);

        $record = CustomerGstYearVerified::where('customer_id', $customerId)
            ->where('gst_year_id', $request->gst_year_id)
            ->first();

        if ($record) {
            // Toggle is_verify
            $record->is_verify = $record->is_verify ? 0 : 1;
            $record->save();
        } else {
            // Create new verification
            $record = CustomerGstYearVerified::create([
                'customer_id' => $customerId,
                'gst_year_id' => $request->gst_year_id,
                'is_verify'   => 1,
            ]);
        }

        return response()->json([
            'success'   => true,
            'verified'  => $record->is_verify,
            'year_id'   => $record->gst_year_id,
            'customer'  => $record->customer_id,
        ]);
    }
}
