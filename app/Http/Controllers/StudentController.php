<?php

namespace App\Http\Controllers;

use App\Models\Student;
use DataTables;
use Illuminate\Http\Request;
use Validator;

class StudentController extends Controller
{
    public function index(Request $request){
        if ($request->ajax()) {

            $data = Student::latest()->get();

            return DataTables::of($data)

                    ->addIndexColumn()

                    ->addColumn('action', function($row){

                        $btn = '<a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Edit" class="edit btn btn-primary btn-sm editStudent">Edit</a>';
                        $btn = $btn.' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-danger btn-sm deleteStudent">Delete</a>';
                        return $btn;

                    })

                    ->rawColumns(['action'])

                    ->make(true);

        }
        $students = Student::latest()->get();
        return view('students',compact('students'));

    }

    public function store(Request $request){ 
        
        $validator = Validator::make($request->all(), [
            'first_name'=>'required',
            'last_name'=>'required',
            'date_of_birth'=>'required|date_format:Y-m-d',
            'percentage'=>'required|numeric',
            'profile_picture' => 'required',

        ]);

        if($validator->passes()){

            $imageName = time().'.'.$request->profile_picture->getClientOriginalExtension();

            $request->profile_picture = $imageName;

            $request->file('profile_picture')->move(public_path('photos'), $imageName);


            Student::updateOrCreate(['id'=>$request->id],
        ['first_name'=>$request->first_name,'last_name'=>$request->last_name,
        'date_of_birth'=>$request->date_of_birth,'percentage'=>$request->percentage,'profile_picture'=>$request->profile_picture]);


        return response()->json(['success'=>'Student saved successfully.']);

        }

        return response()->json(['error'=>$validator->errors()->all()]);
        

        
    }

    public function edit($id){

        $student = Student::find($id);

        return response()->json($student);

    }

    public function destroy($id){
        Student::find($id)->delete();
        return response()->json(['success'=>'Student deleted successfully.']);

    }
}
