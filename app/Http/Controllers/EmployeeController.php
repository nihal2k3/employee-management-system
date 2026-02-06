<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function create()
    {
        return view('employees.create');
    }

    public function store_employee(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'name' => 'required|max:90',
            'email' => 'required|email|unique:employees',
            'phone' => 'required|numeric',
            'salary' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        } else {
            Employee::create([
                'name' => $req->name,
                'email' => $req->email,
                'phone' => $req->phone,
                'salary' => $req->salary,
                'is_deleted' => 0
            ]);
            return response()->json([
                'status' => true,
                'message' => "successfully Added"
            ]);
        }
    }

    public function list()
    {
        $employees = Employee::where('is_deleted', 0)->select(
            'id',
            'name',
            'email',
            'phone',
            'salary',
            'status',
            'created_at',
            'updated_at'
        )->paginate(1);

        return response()->json($employees);
    }

    public function delete(Request $req)
    {
        if (empty($req->id)) {
            return response()->json([
                'status'  => false,
                'message' => 'Employee ID is required'
            ]);
        }
        Employee::where('id', $req->id)->update(['is_deleted' => 1]);

        return response()->json([
            'status' => true,
            'message' => 'Employee deleted successfully'
        ]);
    }

    public function edit(Request $req)
    {
        if (empty($req->id)) {
            return response()->json([
                'status'  => false,
                'message' => 'Employee ID is required'
            ]);
        }
        $employee = Employee::find($req->id);

        if (!$employee) {
            return response()->json(['status' => true, 'message' => 'Employee not found'], 404);
        }

        return response()->json($employee);
    }

    public function update_employee(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'id' => 'required',
            'name' => 'required|max:90',
            'email' => 'required|email',
            'phone' => 'required|numeric',
            'salary' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ]);
        } else {
            Employee::where('id', $req->id)->update([
                'name' => $req->name,
                'email' => $req->email,
                'phone' => $req->phone,
                'salary' => $req->salary,
                'is_deleted' => 0
            ]);
            return response()->json([
                'status' => true,
                'message' => "successfully Updated"
            ]);
        }
    }

    public function search(Request $request)
    {
        $search = $request->search;

        $employees = Employee::where('is_deleted', 0)
            ->where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->get();

        return response()->json($employees);
    }
}
