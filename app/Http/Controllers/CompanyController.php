<?php

namespace App\Http\Controllers;

use App\Exceptions\ConflictHttpException;
use App\Models\Company;
use Illuminate\Http\Request;
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
     * @param $id - user id
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
     * @param Request $request - request object
     * @param $id - user id
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws ConflictHttpException
     */
    public function create(Request $request, $id)
    {
        $this->validate($request, Company::$rules);

        $companyName = $request->input("name");

        $name = Company::where('name', $companyName);

        if ($name) {
            throw new ConflictHttpException("name already taken");
        }

        $company = new Company();
        $company->name = $name;
        $company->location = $request->input("location");
        $company->phone = $request->input("phone");
        $company->email = $request->input("email");
        $company->description = $request->input("description");
        $company->user = $id;
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
     * @throws ConflictHttpException
     */
    public function delete($id, $company_id)
    {
        $company = Company::find($company_id);
        if (!$company) {
            throw new NotFoundException('company not found');
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
    public function getCompanyById($id, Request $request)
    {
        $companies = Company::where("user", $id);
        return response()->json($companies, 200);
    }
}
