<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GSTYear;

class GstYearController extends Controller
{
    public function list() {
        return response()->json(GSTYear::orderBy('label','desc')->get());
    }

    public function save(Request $request) {
        $request->validate([
            'id' => 'nullable|exists:gst_years,id',
            'label' => 'required|string|max:20|unique:gst_years,label,' . $request->id,
        ]);
        $year = GSTYear::updateOrCreate(
            ['id' => $request->id],
            ['label' => $request->label]
        );
        return response()->json(['success'=>true,'data'=>$year,'message'=>'GST Year saved']);
    }
    
    public function destroy($id) {
        GSTYear::findOrFail($id)->delete();
        return response()->json(['success'=>true,'message'=>'GST Year deleted']);
    }

    public function verifiedYear(Request $request) {
        dd($request->all());
        // $gstYear = GSTYear::find($request->gstYear);
        // return response()->json([
        //     'code'=>200,
        //     'success'=>true,
        //     'data' => $gstYear,
        //     'message' => 'GST Year verified'
        // ]);
    }
}
