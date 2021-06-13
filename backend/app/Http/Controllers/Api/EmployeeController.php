<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Helpers\HttpCodes;

class EmployeeController extends Controller
{
    /**
     * List of form fields.
     */
    protected const FIELDS = [
        'first_name',
        'last_name',
        'email',
        'position'
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Employee::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $response = Employee::create(
                $request->only(self::FIELDS)
            );
            $httpStatus = HttpCodes::HTTP_CREATED;
        } catch (\Throwable $t) {
            \Log::error($t->getMessage());
            $response = [
                'error' => 'An error occurred while creating a new employee.'
            ];
            $httpStatus = HttpCodes::HTTP_OK;
        }

        return response()->json($response, $httpStatus);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        return $employee;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        $employee->update($request->only(self::FIELDS));

        return response()->json($employee, HttpCodes::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();

            $response = null;
            $httpStatus = HttpCodes::HTTP_NO_CONTENT;
        } catch (\Throwable $t) {
            \Log::error($t->getMessage());
            $response = [
                'error' => 'An error occurred while deleting an employee.'
            ];
            $httpStatus = HttpCodes::HTTP_OK;
        }

        return response()->json($response, $httpStatus);
    }
}
