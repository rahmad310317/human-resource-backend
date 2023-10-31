<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateRoleRequest;
use App\Http\Requests\UpdateRoleRequest;


class RoleController extends Controller
{
    public function fetch(Request $request)
    {

        try {
            $id = $request->input('id');
            $name = $request->input('name');
            $limit = $request->input('limit', 10);
            $with_responsibilities = $request->input('with_responsibilities', false);
            $roleQuery = Role::withCount('responsibilities');

            // Get single data
            if ($id) {
                $role = $roleQuery->with('responsibilities')->find($id);

                if ($role) {
                    return ResponseFormatter::success($role, 'Role found');
                }

                return ResponseFormatter::error('Role not found', 404);
            }

            // Get multiple data
            $roles = $roleQuery->where('company_id', $request->company_id);

            if ($name) {
                $roles->where('name', 'like', '%' . $name . '%');
            }

            if ($with_responsibilities) {
                $roles->with('responsibilities');
            }

            return ResponseFormatter::success(
                $roles->paginate($limit),
                'Roles found'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error);
        }
    }

    public function create(CreateRoleRequest $request)
    {

        try {
            // Create Role
            $role = Role::create([
                'name' => $request->name,
                'company_id' => $request->company_id,
            ]);
            // check validasi
            if (!$role) {
                throw new Exception('Role not Created');
            }
            // response succes
            return ResponseFormatter::success($role, 'Role created');
        } catch (Exception $error) {
            // response error
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
    public function update(UpdateRoleRequest $request, $id)
    {

        try {

            $role = Role::find($id);

            // check validasi
            if (!$role) {
                throw new Exception('Role not update');
            }

            $role->update([
                'name' =>  $request->name,
                'company_id' =>  $request->company_id,
            ]);
            // response succes
            return ResponseFormatter::success($role, 'Role updated');
        } catch (Exception $error) {
            // response error
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
    public function destroy($id)
    {

        try {
            // Get data
            $role = Role::find($id);

            // check validasi
            if (!$role) {
                throw new Exception('Role not found');
            }
            // delete role
            $role->delete();
            // response succes
            return ResponseFormatter::success($role, 'Role deleted');
        } catch (Exception $error) {
            // response error
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
