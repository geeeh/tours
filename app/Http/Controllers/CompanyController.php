<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exceptions\NotFoundException;

/**
 * Company Controller.
 */
class CompanyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
        $this->middleware(
            'auth', [
                'only'=>[
                    'create',
                    'modify',
                    'delete',
                    'getCompanyByCurrentUser'
                    ]
                ]
        );
    }

    /**
     * Get all companies.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAll()
    {
       $companies = Company::all();
       return response()->json($companies, 200);
    }

    /**
     * Add new company entry.
     * 
     * @param Request $request - request object.
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $this->validate($request, Company::$rules);

        $company = new Company();
        $company->name = $request->input("name");
        $company->location = $request->input("location");
        $company->phone = $request->input("phone");
        $company->email = $request->input("email");
        $company->description = $request->input("description");
        $company->user = $request->user()->id;
        $company->save();

        return response()->json($company, 201);
    }

    /**
     * Delete a company by id.
     *
     * @param integer - $id - event id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @throws NotFoundException
     */
    public function delete($id)
    {
        $company = Company::find($id);
        if (!$company) {
            throw new NotFoundException();
        }
        $company->delete();
        return response()->json('deleted', 200);
    }

    /**
     * Get companies registered by curent user.
     * 
     * @param Request $request - request object.
     * 
     * @return Response - response object.
     */
    public function getCompanyByCurrentUser(Request $request)
    {
        $companies = Company::where("user", $request->user()->id);
        if ($companies) {
            return response()->json($companies->get(), 200);
        }
        return response()->json([], 200);
    }
}
