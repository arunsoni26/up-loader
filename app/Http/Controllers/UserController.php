<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\User;
use App\Models\UserPermission;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index() {
        return view('admin.users.index');
    }

    public function list(Request $request) {
        $query = User::with('role') // Eager load group relation
            ->select('id', 'name', 'email', 'status', 'role_id')
            ->where('role_id', 2);

        // Filters
        if ($request->role_id) {
            $query->where('group_id', $request->group_id);
        }
        if ($request->status !== null && $request->status !== '') {
            $query->where('status', $request->status);
        }
        // if ($request->code) {
        //     $query->where('code', 'LIKE', "%{$request->code}%");
        // }

        $users = $query->get();

        // Format Data
        $data = $users->map(function ($row) {
            $allRows = [
                'name' => $row->name,
                'email' => $row->email,
                'role' => $row->role->name ?? '-',
                'status_toggle' => '',
                'actions' => view('admin.users.partials.actions', compact('row'))->render()
            ];
    
            if(canDo('users','can_edit')) {
                $allRows['status_toggle'] = '
                    <div class="form-check form-switch">
                        <input 
                            type="checkbox"
                            class="form-check-input toggle-status"
                            data-id="'.$row->id.'" '.($row->status ? 'checked' : '').'>
                    </div>
                ';
            }
            return $allRows;
        });

        return response()->json(['data' => $data]);
    }

    public function form(Request $request) {
        $user = $request->userId ? User::findOrFail($request->userId) : null;
        return view('admin.users.add-edit-form', compact('user'));
    }

    public function save(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . ($request->id ?? 'NULL'),
            'password' => $request->id ? 'nullable|min:6' : 'required|min:6',
        ]);

        $user = $request->id ? User::findOrFail($request->id) : new User();

        $user->name = $request->name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->role_id = $request->role_id ?? 2;
        $user->status = $request->status ?? 1;
        $user->save();

        return response()->json([
            'code' => 200,
            'success' => true,
            'msg' => 'User saved successfully',
        ]);
    }

    public function toggleStatus($id) {
        $user = User::findOrFail($id);
        $user->status = !$user->status;
        $user->save();
        return response()->json(['success' => true]);
    }

    public function destroy($id) {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['success' => true]);
    }

    public function userPermissionForm(Request $request)
    {
        $user = User::findOrFail($request->userId);
        $modules = Module::all();
        $userPermissions = UserPermission::where('user_id', $user->id)->get()->keyBy('module_id');

        $html = view('admin.user-permissions.permissions', [
            'user' => $user,
            'modules' => $modules,
            'userPermissions' => $userPermissions,
        ])->render();

        return $html;
    }

    public function updatePermission(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'permissions' => 'array'
        ]);

        $userId = $request->input('user_id');
        $permissions = $request->input('permissions', []);

        try {
            DB::transaction(function () use ($userId, $permissions) {
                // remove old permissions
                UserPermission::where('user_id', $userId)->delete();

                $now = now();
                $inserts = [];

                foreach ($permissions as $moduleId => $actions) {
                    $inserts[] = [
                        'user_id'      => $userId,
                        'module_id'    => (int)$moduleId,
                        'can_view_nav' => isset($actions['can_view_nav']) ? 1 : 0,
                        'can_access'   => isset($actions['can_access']) ? 1 : 0,
                        'can_add'      => isset($actions['can_add']) ? 1 : 0,
                        'can_view'     => isset($actions['can_view']) ? 1 : 0,
                        'can_edit'     => isset($actions['can_edit']) ? 1 : 0,
                        'created_at'   => $now,
                        'updated_at'   => $now,
                    ];
                }

                if (!empty($inserts)) {
                    DB::table('user_permissions')->insert($inserts);
                }
            });

            return response()->json(['code' => 200, 'msg' => 'Permissions updated successfully.']);
        } catch (\Throwable $e) {
            // log($e->getMessage());
            dd($e->getMessage());
            return response()->json(['code' => 500, 'msg' => 'Failed to update permissions.'], 500);
        }
    }
}
