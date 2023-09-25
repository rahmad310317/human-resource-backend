<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use Exception;
use Illuminate\Http\Request;
use App\Models\Responsibility;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateResponsibilityRequest;
use App\Http\Requests\UpdateResponsibilityRequest;


class ResponsibilityController extends Controller
{
    public function fetch(Request $request)
    {
        try {
            $id = $request->input('id');
            $name = $request->input('name');
            $limit = $request->input('limit', 10);
            $responsibilityQuery = Responsibility::query();

            // get single data 
            if ($id) {
                $responsibility = $responsibilityQuery->find($id);

                if ($responsibility) {
                    return ResponseFormatter::success($responsibility, 'Responsibilily Found');
                }

                return ResponseFormatter::error("Responsibility not found");
            }

            // get multi data
            $responsibilities = $responsibilityQuery->where('role_id', $request->role_id);

            if ($responsibilities) {
                $responsibilities->where('name', 'like', '%' . $name . '%');
            }

            // response success
            return ResponseFormatter::success(
                $responsibilities->paginate($limit),
                'Responsibility Found'
            );
        } catch (Exception $error) {
            return ResponseFormatter::error('Responsibilty Failed', $error);
        }
    }

    public function create(CreateResponsibilityRequest $request)
    {
        try {
            // Create responsibility
            $responsibility = Responsibility::create([
                'name' => $request->name,
                'role_id' => $request->role_id,
            ]);

            if (!$responsibility) {
                throw new Exception('Responsibilily not create');
            }

            // response succes
            return ResponseFormatter::success($responsibility, 'Responsibilty Created');
        } catch (Exception $error) {
            return ResponseFormatter::error('Responsibilty Failed', $error);
        }
    }

    public function update(UpdateResponsibilityRequest $request, $id)
    {
        try {
            // get data responsibility
            $responsibility = Responsibility::find($id);

            // cek if responsibility exits
            if (!$responsibility) {
                throw new Exception('Responsibility not found', 500);
            }

            // update responsibility
            $responsibility->update([
                'name' => $request->name,
                'role_id' => $request->role_id
            ]);

            return ResponseFormatter::success($responsibility, 'Repsonsibility Update');
        } catch (Exception $error) {
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            // get data
            $responsibility = Responsibility::find($id);

            // cek responsibility exist
            if (!$responsibility) {
                throw new Exception('Responsibility Not Found');
            }

            // delete responsibility
            $responsibility->delete();

            // response success
            return ResponseFormatter::success('Responsibilty Deleted');
        } catch (Exception $error) {
            // response error
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }
}
