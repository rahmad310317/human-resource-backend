<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;

class CompanyController extends Controller
{
    public function fecth(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        if ($id) {
            $company = Company::with(['users'])->find($id);

            if ($company) {
                return ResponseFormatter::success($company, 'Company Found');
            }
            return ResponseFormatter::error('Company Not Found', 404);
        }

        $companies = Company::with(['users']);
        if ($name) {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Companies Found'
        );
    }

    public function create(CreateCompanyRequest $request)
    {
        try {
            // TODO: Upload logo
            if ($request->hasFile('logo')) {
                $path  = $request->file('logo')->store('public/logos');
            }

            // TODO: Create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path
            ]);
            //  Cek validasi
            if (!$company) {
                throw new Exception('Company Not Created');
            }

            // TODO: Attach company to user
            $user  = User::find(Auth::id());
            $user->companies()->attach($company->id);

            // TODO: load user et company  
            $company->load('users');

            // TODO: response success
            return ResponseFormatter::success($company, 'Company Created');
        } catch (Exception $error) {
            // TODO: response error
            return ResponseFormatter::error($error->getMessage(), 500);
        }
    }

    public function update(UpdateCompanyRequest $request)
    {
        try {
            // TODO: Get company
            // TODO: Check if company exist
            // TODO: Upload logo
            // TODO: update company
        } catch (Exception $error) {
            // TODO: response error
        }
    }
}
