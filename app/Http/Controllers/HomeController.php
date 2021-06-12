<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */

    public function create()
    {
//
    }

/**
 * Store a newly created resource in storage.
 *
 * @param \Illuminate\Http\Request $request
 * @return \Illuminate\Http\Response
 */
    public function getAllEmployees()
    {
        $employees = DB::table('employees')->get();
        return view('list_employees', compact('employees'));
    }

    public function postAddtime(Request $request)
    {
        $this->validate($request, [
            'monday_start_time' => 'required',
            'monday_end_time' => 'required',
            'tuesday_start_time' => 'required',
            'tuesday_end_time' => 'required',
            'wednesday_start_time' => 'required',
            'wednesday_end_time' => 'required',
            'thursday_start_time' => 'required',
            'thursday_end_time' => 'required',
            'friday_start_time' => 'required',
            'friday_end_time' => 'required',
            'saturday_start_time' => 'required',
            'saturday_end_time' => 'required',
        ]);

        DB::table('times')->update([
            'monday_start_time' => $request->monday_start_time,
            'monday_end_time' => $request->monday_end_time,
            'tuesday_start_time' => $request->tuesday_start_time,
            'tuesday_end_time' => $request->tuesday_end_time,
            'wednesday_start_time' => $request->wednesday_start_time,
            'wednesday_end_time' => $request->wednesday_end_time,
            'thursday_start_time' => $request->thursday_start_time,
            'thursday_end_time' => $request->thursday_end_time,
            'friday_start_time' => $request->friday_start_time,
            'friday_end_time' => $request->friday_end_time,
            'saturday_start_time' => $request->saturday_start_time,
            'saturday_end_time' => $request->saturday_end_time,

        ]);

        return redirect()->back()->with('info', 'You have Added time Successfully!');
    }

    public function postAddEmployee(Request $request)
    {

        $this->validate($request, [
            'surname' => 'required',
            'name' => 'required',
            'address' => 'required',
            'province' => 'required',
            'mobile' => 'required|integer|min:12',
            'email' => 'required|unique:employees',
            'account_number' => 'required|unique:employees',
            'account_type' => 'required',
            'bank' => 'required',
            'emergency_number' => 'digits_between:7,12|required',
            'salary' => 'required',
            'working_hours' => 'required',
            'type'=> 'required',
        ]);

        DB::table('employees')->insert([
            'surname' => $request->surname,
            'name' => $request->name,
            'address' => $request->address,
            'province' => $request->province,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'account_number' => $request->account_number,
            'account_type' => $request->account_type,
            'bank' => $request->bank,
            'emergency_number' => $request->emergency_number,
            'salary' => $request->salary,
            'working_hours' => $request->working_hours,
            'workers_type'=> $request->type,

        ]);

        return redirect()->back()->with('info', 'You have Added User Successfully!');
    }

    public function postCreateTask(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'description' => 'required',

        ]);

// dd($request->emergency_number);
        DB::table('tasks_and_subtasks')->insert([
            'parent_id' => 0,
            'task' => $request->name,
            'description' => $request->description,

        ]);

        return redirect()->back()->with('info', 'You have Added Task Successfully!');
    }

    public function postCreateSubTask(Request $request)
    {

        $this->validate($request, [
            'task_id' => 'required',
            'subtasks' => 'required',
            'description' => 'required',

        ]);

// dd($request->emergency_number);
        DB::table('tasks_and_subtasks')->insert([

            'parent_id' => $request->task_id,
            'task' => $request->subtasks,
            'description' => $request->description,
        ]);

        return redirect()->back()->with('message', 'You have Added SubTask Successfully!');
    }

    public function SearchProject(Request $request)
    {
        $fecha = $request->fecha;
        $work = $request->work;
        if(isset($request->fecha) && isset($request->work)){

            $projects = DB::table('projects')->where('status', $request->work)->where('start_date', $request->fecha)->get();
            return view('search_project', compact('projects','work','fecha'));
            }
            else if(isset($request->fecha)){

                $projects = DB::table('projects')->where('start_date', $request->fecha)->get();
                
                return view('search_project', compact('projects','fecha'));
                
            }
            
            else if (isset($request->work)){
                $projects = DB::table('projects')->where('status', $request->work)->get();
                
                return view('search_project', compact('projects','work'));


            }
                
            return redirect()->back();

        

        // $count = 0;
        // $search_date = DB::table('projects')->where('start_date', $request->fecha)->get();
        // if (isset($request->work)) {
        //     foreach ($search_date as $date) {
        //         $projects[$count] = DB::table('projects')->where('status', $request->work)->where('start_date', $request->fecha)->first();
        //         $count = $count + 1;

        //     }
        //     dd($projects);
        // }

        // return redirect()->back()->with('message', 'You have Added SubTask Successfully!');
    }

    public function postAssignTask(Request $request)
    {

        $this->validate($request, [
            'project_id' => 'required',
            'task_id' => 'required',
            'date' => 'required',
            'employee_id_1' => 'required',
        ]);

// dd($request->emergency_number);
        DB::table('assignments')->insert([

            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'date' => $request->date,
            'employee_id_1' => $request->employee_id_1,
            'employee_id_2' => $request->employee_id_2,
            'employee_id_3' => $request->employee_id_3,
            'employee_id_4' => $request->employee_id_4,

        ]);

        return redirect()->back()->with('info', 'You have Assigned Task Successfully!');
    }

    public function postAddMaterials(Request $request)
    {

        $this->validate($request, [
            'manufacturer' => 'required',
            'dimension' => 'required',
            'height' => 'required',
            'length' => 'required',
            'width' => 'required',
            'depth' => 'required',
            'diameter' => 'required',
            'types_of_material' => 'required',
            'quality' => 'required',

        ]);

// dd($request->emergency_number);
        DB::table('materials')->insert([

            'manufacturer_name' => $request->manufacturer,
            'dimension' => $request->dimension,
            'height' => $request->height,
            'length' => $request->length,
            'width' => $request->width,
            'depth' => $request->depth,
            'diameter' => $request->diameter,
            'types_of_materials' => $request->types_of_material,
            'quality_of_finishes' => $request->quality,

        ]);

        return redirect()->back()->with('info', 'You have Added Materials Successfully!');
    }

    public function add_user(Request $request)
    {

        $this->validate($request, [
            'username' => 'required|unique:users',
            'password' => 'min:6|required_with:confirm_password|same:confirm_password',
            'confirm_password' => 'min:6',
        ]);

        DB::table('users')->insert([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'new_employee' => $request->has('new_employee') ? 1 : 0,
            'list_employees' => $request->has('list_employees') ? 1 : 0,
            'history_employees' => $request->has('history_employees') ? 1 : 0,
            'project_list' => $request->has('project_list') ? 1 : 0,
            'production' => $request->has('production') ? 1 : 0,
            'dedicated_time' => $request->has('dedicated_time') ? 1 : 0,
            'create_tool' => $request->has('create_tool') ? 1 : 0,
            'list_tools' => $request->has('list_tools') ? 1 : 0,
            'history_tools' => $request->has('history_tools') ? 1 : 0,
            'create_state' => $request->has('create_state') ? 1 : 0,
        ]);

        return redirect()->back()->with('info', 'You have Added User Successfully!');
    }

    public function postAddProject(Request $request)
    {

        $this->validate($request, [
            'project' => 'required',
            'location' => 'required',
            'customer' => 'required',
            'contact_person' => 'required|min:11',
            'engineer_incharge' => 'required',
            'amount' => 'required',
            'start_date' => 'required',
            'delivery_date' => 'required|after:start_date',
            'video' => 'required',

        ]);

        if ($request->file('video')) {
            $file = $request->file('video');
            $filename = $file->getClientOriginalName();
            $path = public_path() . '/videos/';
            $file->move($path, $filename);

            DB::table('projects')->insert([
                'project' => $request->project,
                'location' => $request->location,
                'customer' => $request->customer,
                'contact_person' => $request->contact_person,
                'engineer_incharge' => $request->engineer_incharge,
                'amount' => $request->amount,
                'start_date' => $request->start_date,
                'delivery_date' => $request->delivery_date,
                'file' => $filename,

            ]);
            $project_id = DB::table('projects')->orderby('id', 'desc')->first();

            DB::table('project_details')->insert([
                'project_id' => $project_id->id,
                'subtask_id' => $request->task_id,
                'quantity' => $request->quantity,
                'location' => $request->locations,
                'direction' => $request->directions,

            ]);

            return redirect()->back()->with('info', 'You have Added Project Successfully!');

        };

        return redirect()->back()->with('info', 'Something Wrong');

    }

    public function postchangeStatus(Request $request)
    {
        DB::table('projects')->where('id', $request->id)->update([
            'status' => $request->status]);
        return redirect()->back()->with('info', 'Successfully Updated Status');
    }

    public function edit_user($id)
    {
        $edit_user = DB::table('users')->where('id', $id)->first();
        return view('editpage', compact("edit_user"));
    }
    public function update(Request $request)
    {
        $this->validate(request(), [
            'username' => 'required',
        ]);

        DB::table('users')->where('id', $request->id)->update([
            'username' => $request->username,
            'new_employee' => $request->has('new_employee') ? 1 : 0,
            'list_employees' => $request->has('list_employees') ? 1 : 0,
            'history_employees' => $request->has('history_employees') ? 1 : 0,
            'project_list' => $request->has('project_list') ? 1 : 0,
            'production' => $request->has('production') ? 1 : 0,
            'dedicated_time' => $request->has('dedicated_time') ? 1 : 0,
            'create_tool' => $request->has('create_tool') ? 1 : 0,
            'list_tools' => $request->has('list_tools') ? 1 : 0,
            'history_tools' => $request->has('history_tools') ? 1 : 0,
            'create_state' => $request->has('create_state') ? 1 : 0,
        ]);
        return redirect('user')->with('info', 'You have Edited User Successfully!');
    }

    public function postRegisterMaterials(Request $request)
    {
        $this->validate(request(), [
            'invoice' => 'required',
            'date' => 'required',
            'supplier' => 'required',

        ]);

        $items = $request->get('item');

        foreach ($items as $i => $item) {
            DB::table('register_materials')->insert([
                'invoice' => $request->invoice,
                'date' => $request->date,
                'supplier' => $request->supplier,
                'item' => $items[$i],
                'quantity' => $request->get('quantity')[$i],
                'price' => $request->get('price')[$i],
            ]);

        }

        return redirect()->back()->with('info', 'You have Registerd Materials Successfully!');
    }

    public function delete_user($id)
    {
        DB::table('users')->where('id', $id)->delete();
        return redirect()->back()->with('info', 'You have Deleted User Successfully!');
    }

    public function gettest()
    {
        $view_users = DB::table('users')->where('username', '!=', 'admin')->get();
        return view('users', compact("view_users"));
    }

    public function daily_worker_performance()
    {
        $employees = DB::table('employees')->get();
        return view('daily_worker_performance', compact('employees'));
    }

    public function post_daily_worker_performance(Request $request)
    {
        $this->validate($request,
            [
                'employee_id' => 'required',
                'project_id' => 'required',
                'task_id' => 'required',
                'requirements' => 'required',
                'working_time' => 'required',
                'objective' => 'required',
                'date' => 'required',
            ]);

        DB::table('daily_worker_performance')->insert([
            'employee_id' => $request->employee_id,
            'project_id' => $request->project_id,
            'task_id' => $request->task_id,
            'requirements' => $request->requirements,
            'working_time' => $request->working_time,
            'finished_units' => $request->finished_units,
            'objective' => $request->objective,
            'faulty_units' => $request->faulty_units,
            'valued_loss' => $request->valued_loss,
            'commentary' => $request->commentary,
            'date' => $request->date,

        ]);

        return redirect()->back()->with('info', 'Successfully Added');
    }

}
