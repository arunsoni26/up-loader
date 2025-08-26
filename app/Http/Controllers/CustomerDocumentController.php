<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerDocument;
use App\Models\DocType;
use App\Models\GSTYear;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CustomerDocumentController extends Controller
{
    public function index(Customer $customer)
    {
      	if (auth()->user()->role->slug == 'customer' && auth()->user()->id !== $customer->user_id) {
          	abort(403, 'Unauthorized access.');
        }
        $years = GSTYear::orderBy('label','desc')->get();
        $docTypeQuery = DocType::where('status', 1);
        
        if (auth()->user()->role->slug == 'customer') {
            $docTypeQuery->where('is_show', operator: 1);
        }
        $docTypes = $docTypeQuery->get();

        return view('admin.customers.docs.index', compact('customer','years','docTypes'));
    }

    public function list(Request $request, Customer $customer)
    {
        // Access control
        if (auth()->user()->role->slug === 'customer' && auth()->user()->id !== $customer->user_id) {
            abort(403, 'Unauthorized access.');
        }

        // Build query
        $query = CustomerDocument::with(['gstYear', 'uploader', 'docType'])
            ->where('customer_id', $customer->id);

        // Filter based on role
        if (auth()->user()->role->slug === 'customer') {
            $query->whereHas('docType', fn($q) => $q->where('is_show', 1));
        }

        // Apply filters
        if ($request->filled('gst_year_id')) {
            $query->where('gst_year_id', $request->gst_year_id);
        }
        if ($request->filled('doc_type')) {
            $query->where('doc_type', $request->doc_type);
        }

        // Get documents
        $documents = $query->orderByDesc('created_at')->get();

        // Format data
        $data = $documents->map(function ($doc) {
            // dd($doc->doc_type, $doc->docType);
            return [
                'year' => $doc->gstYear?->label ?? '-',
                'type' => $doc->docType->name ?? '-',
                'desc' => e($doc->description) ?? '-',
                'file' => '<a href="'.route('admin.customers.docs.download', [$doc->customer_id, $doc->id]).'" class="btn btn-sm btn-outline-primary"><i class="fa fa-download"></i> Download</a>',
                'by' => $doc->uploader?->name ?? '—',
                'date' => $doc->created_at->format('d M Y'),

                'actions' => auth()->user()->hasPermission('customer_docs', 'can_add', auth()->id())
                    ? '<button class="btn btn-sm btn-danger deleteDoc" data-id="'.$doc->id.'"><i class="fa fa-trash"></i></button>'
                    : ''
            ];
        });

        // Return in DataTable format
        return response()->json(['data' => $data]);
    }


    // Modal form
    public function form(Request $request, Customer $customer)
    {
        $years = GSTYear::orderBy('label','desc')->get();
        
        $docTypesQuery = DocType::where('status', 1);
        if (auth()->user()->role->slug == 'customer') {
            $docTypesQuery->where('is_show', 1);
        }
        $docTypes = $docTypesQuery->get();

        return view('admin.customers.docs.partials.form', [
            'customer' => $customer,
            'years'    => $years,
            'docTypes'    => $docTypes
        ]);
    }

    // Save multiple docs for one GST year + many types
    public function save(Request $request, Customer $customer)
    {
        $request->validate([
            'gst_year_id' => 'required|exists:gst_years,id',
            'docs.*.*.file' => 'nullable|file|max:10240', 
            'docs.*.*.description' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $uploaded = 0;
            $docs = $request->input('docs', []);

            foreach ($docs as $docTypeId => $rows) {
                foreach ($rows as $index => $row) {
                    $fileInputName = "docs.$docTypeId.$index.file";
                    $desc          = $row['description'] ?? null;

                    if ($request->hasFile($fileInputName)) {
                        $file     = $request->file($fileInputName);
                        $filename = now()->format('Ymd_His') . '_' . Str::random(6) . '.' . $file->getClientOriginalExtension();

                        $path = $file->storeAs(
                            "customers/{$customer->id}/{$request->gst_year_id}/{$docTypeId}", 
                            $filename, 
                            's3'
                        );

                        CustomerDocument::create([
                            'customer_id' => $customer->id,
                            'gst_year_id' => $request->gst_year_id,
                            'doc_type' => $docTypeId,
                            'description' => $desc,
                            'file_path'   => $path,
                            'uploaded_by' => auth()->id(),
                        ]);
                        $uploaded++;
                    }
                }
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => $uploaded ? "Uploaded $uploaded document(s)." : "No files selected.",
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['success'=>false,'message'=>$e->getMessage()], 422);
        }
    }

    public function download(Customer $customer, $id)
    {
        $doc = CustomerDocument::where('customer_id',$customer->id)->findOrFail($id);
        return Storage::disk('s3')->download($doc->file_path);
    }

    public function destroy(Customer $customer, $id)
    {
        $doc = CustomerDocument::where('customer_id',$customer->id)->findOrFail($id);
        $doc->delete(); // soft delete
        return response()->json(['success'=>true,'message'=>'Document deleted']);
    }
}
