<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\Employees;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;

class EmployeeController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $id = $request->input('id');
            $name = $request->input('name');
            $email = $request->input('email');
            $age = $request->input('age');
            $phone = $request->input('phone');
            $team_id = $request->input('team_id');
            $role_id = $request->input('role_id');
            $company_id = $request->input('company_id');
            $limit = $request->input('limit', 10);

            $employeeQuery = Employees::with('team', 'role');
            // Get single data
            if ($id) {
                $employee = $employeeQuery->with(['team', 'role'])->find($id);

                if ($employee) {
                    return ResponseFormatter::success($employee, 'Employee found');
                }

                return ResponseFormatter::error('Employee not found', 404);
            }


            // Get multiple data
            $employees = $employeeQuery;

            if ($name) {
                $employees->where('name', 'like', '%' . $name . '%');
            }
            if ($email) {
                $employees->where('email', $email);
            }
            if ($age) {
                $employees->where('age', $age);
            }
            if ($phone) {
                $employees->where('phone', 'like', '%' . $phone . '%');
            }
            if ($role_id) {
                $employees->where('role_id', $role_id);
            }
            if ($team_id) {
                $employees->where('team_id', $team_id);
            }

            if ($company_id) {
                $employees->whereHas('team', function ($query) use ($company_id) {
                    $query->where('company_id', $company_id);
                });
            }
            return ResponseFormatter::success(
                $employees->paginate($limit),
                'Employees found'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 404);
        }
    }

    public function create(CreateEmployeeRequest $request)
    {
        try {
            // upload photo

            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos');
            }

            // creta Employees

            $employee = Employees::create([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => $path,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);
            // cek update employee
            if (!$employee) {
                throw new Exception('Employee not Created');
            }
            // return repsonse success
            return responseFormatter::success('Employees Created', $employee);
        } catch (Exception $error) {
            return responseFormatter::error($error->getMessage(), 500);
        }
    }
    public function update(UpdateEmployeeRequest $request, $id)
    {
        try {
            // get data
            $employee = Employees::find($id);

            // cek if employees exist
            if (!$employee) {
                throw new Exception('Employees not found');
            }
            // upload photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('public/photos');
            }

            // Update employee
            $employee->update([
                'name' => $request->name,
                'email' => $request->email,
                'gender' => $request->gender,
                'age' => $request->age,
                'phone' => $request->phone,
                'photo' => isset($path) ? $path : $employee->photo,
                'team_id' => $request->team_id,
                'role_id' => $request->role_id,
            ]);
            // response success
            return ResponseFormatter::success($employee, 'Employee updated');
        } catch (Exception $error) {
            // response error
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // get data
            $employee = Employees::find($id);

            // cek if employees exist
            if (!$employee) {
                throw new Exception('Employee not found');
            }

            // delete employees
            $employee->delete();

            return ResponseFormatter::success($employee, 'Employee Deleted');
        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }
    }
}
