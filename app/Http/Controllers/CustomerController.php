<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerGroup;
use App\Models\GSTYear;
use App\Models\User;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CustomerController extends Controller
{
    public function index()
    {
        $groups = CustomerGroup::orderBy('name')->get();
        return view('admin.customers.index', compact('groups'));
    }

    public function list(Request $request)
    {
        $query = Customer::with('group') // Eager load group relation
            ->select('id', 'name', 'email', 'pan', 'father_name', 'client_type_status', 'group_id', 'status', 'hide_dashboard');

        // Filters
        if ($request->group_id) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->code) {
            $query->where('code', 'LIKE', "%{$request->code}%");
        }
        if ($request->gstName) {
            $query->where('gst_name', 'LIKE', "%{$request->gstName}%");
        }

        $customers = $query->get();

        // Format Data
        $data = $customers->map(function ($row) {
            return [
                'name' => $row->name,
                'email' => $row->email,
                'pan' => $row->pan ?? '-',
                'fathers_name' => $row->father_name ?? '-',
                'client_type_status' => ucfirst($row->client_type_status),
                'group' => $row->group->name ?? '-',
                
                'status_toggle' => '
                    <div class="form-check form-switch">
                        <input 
                            type="checkbox"
                            class="form-check-input toggle-status"
                            data-id="'.$row->id.'" '.($row->status ? 'checked' : '').'>
                    </div>
                ',

                'dashboard_toggle' => '
                    <div class="form-check form-switch">
                        <input 
                            type="checkbox"
                            class="form-check-input toggle-dashboard"
                            data-id="'.$row->id.'" '.($row->hide_dashboard ? 'checked' : '').'>
                    </div>
                ',

                'actions' => view('admin.customers.partials.actions', compact('row'))->render()
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function toggleStatus($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->status = !$customer->status;
        $customer->save();
        return response()->json(['success' => true]);
    }

    public function toggleDashboard($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->hide_dashboard = !$customer->hide_dashboard;
        $customer->save();
        return response()->json(['success' => true]);
    }
    
    public function form(Request $request)
    {
        $groups = CustomerGroup::all();
        $customer = $request->customerId ? Customer::findOrFail($request->customerId) : null;
        return view('admin.customers.partials.add-edit-form', compact('customer', 'groups'));
    }

    public function save(Request $request)
    {
        $customerId = $request->id;

        $rules = [
            'name' => 'required|string|max:255',
            'gst_name' => 'nullable|string|max:255',
            'pan' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:50|unique:customers,code,' . $request->id,
            'client_type_status' => 'nullable|string|max:50',
            'fathers_name' => 'nullable|string|max:255',
            'group_id' => 'nullable|integer|exists:customer_groups,id',
            'password' => $request->id ? 'nullable|min:6|confirmed' : 'required|min:6|confirmed',
        ];
        // Password rules
        if ($customerId) {
            // On update → optional but must be confirmed if provided
            if ($request->filled('password')) {
                $rules['password'] = 'nullable|min:6|confirmed';
            }
        } else {
            // On create → required and must be confirmed
            $rules['password'] = 'required|min:6|confirmed';
        }

        if ($customerId) {
            $rules['email'] = [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore(
                    optional(Customer::find($customerId))->user_id
                ),
            ];
        } else {
            // Create case → email required
            $rules['email'] = [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ];
        }

        $request->validate($rules);

        DB::beginTransaction();

        try {
            // Save to users table
            $customerDetails = Customer::find($customerId);
            if ($customerDetails && isset($customerDetails->user_id)) {
                $user = User::find($customerDetails->user_id);
            } else {
                $user = new User();
            }

            $user->name = $request->name;
            $user->email = $request->email;
            // Only update password if filled
            if ($request->filled('password') && !empty($request->password)) {
                $user->password = bcrypt($request->password);
            }
            
            $user->role_id = 3;
            $user->save();

            // Save to customers table
            if ($request->id) {
                $customer = Customer::findOrFail($request->id);
            } else {
                $customer = new Customer();
                $customer->user_id = $user->id;
            }

            $customer->name = $request->name;
            $customer->gst_name = $request->gst_name;
            $customer->pan = $request->pan;
            $customer->code = $request->code;
            $customer->client_type_status = $request->client_type_status;
            $customer->father_name = $request->fathers_name;
            $customer->group_id = $request->group_id;
            $customer->email = $request->email;
            $customer->city = $request->city;
            $customer->dob = $request->dob;
            $customer->gst = $request->gst;
            $customer->aadhar = $request->aadhar;
            $customer->address = $request->address;
            $customer->mobile_no = $request->mobile_no;
            $customer->updated_by = auth()->id();

            // Only update customer password if filled
            if ($request->filled('password') && !empty($request->password)) {
                $customer->password = $request->password;
            }
            
            // ===== S3 Uploads =====
            $disk = 's3'; // change to 'local' for dev if you like
            $base = "customers_details/{$user->id}";

            // PAN
            if ($request->hasFile('pan_doc')) {
                // delete old if present
                if (!empty($customer->pan_doc)) {
                    Storage::disk($disk)->delete($customer->pan_doc);
                }
                $panPath = $request->file('pan_doc')->store("$base/pan", $disk);
                $customer->pan_doc = $panPath; // store path in DB
            }

            // GST
            if ($request->hasFile('gst_doc')) {
                if (!empty($customer->gst_doc)) {
                    Storage::disk($disk)->delete($customer->gst_doc);
                }
                $gstPath = $request->file('gst_doc')->store("$base/gst", $disk);
                $customer->gst_doc = $gstPath;
            }

            // Aadhar
            if ($request->hasFile('aadhar_doc')) {
                if (!empty($customer->aadhar_doc)) {
                    Storage::disk($disk)->delete($customer->aadhar_doc);
                }
                $aadharPath = $request->file('aadhar_doc')->store("$base/aadhar", $disk);
                $customer->aadhar_doc = $aadharPath;
            }
            // ===== /S3 Uploads =====

            $customer->save();

            DB::commit();

            return response()->json(['code' => 200, 'success' => true, 'message' => 'Customer saved successfully.']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['code' => 500, 'success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function view(Request $request)
    {
        $customer = Customer::findOrFail($request->custId);
        $user = User::where('id', $customer->user_id)->first();
        return view('admin.customers.partials.view', compact('customer', 'user'));
    }
    
    public function groupList()
    {
        $groups = CustomerGroup::orderBy('name')->get();
        return response()->json($groups);
    }
    
    public function groupForm(Request $request)
    {
        $group = null;
        if ($request->id) {
            $group = CustomerGroup::find($request->id);
        }
        return view('admin.customers.groups.add-edit-form', compact('group'));
    }

    public function groupSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required|string|max:255|unique:customer_groups,name,' . $request->id,
        ]);

        $group = CustomerGroup::updateOrCreate(
            ['id' => $request->id],
            ['name' => $request->name]
        );

        return response()->json([
            'code' => 200,
            'success' => true,
            'message' => 'Group saved successfully.'
        ]);
    }

    public function groupDelete(Request $request)
    {
        CustomerGroup::findOrFail($request->groupId)->delete();
        return response()->json(['success' => true, 'message' => 'Group deleted successfully.']);
    }

    public function downloadCustomers(Request $request) {
        $gstYears = GSTYear::all();
        $customers = Customer::with(relations: ['verifiedYears.gstYear'])->get();
        return view('admin.customers.downloads.customer-list', compact('customers', 'gstYears'));
    }
}
