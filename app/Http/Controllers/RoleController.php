<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Carbon\Carbon;
use DB;
use Validator;
use App\Http\Controllers\Controller;
use App\Role;
use App\User;
use Auth;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* RBAC */
        if (! User::authorize('role.index')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }

        return view('role.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* RBAC */
        if (! User::authorize('role.create')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }

        return view('role.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /* RBAC */
        if (! User::authorize('role.create')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'display_name' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(array('status' => 0, 'message' => $validator->errors()->first()));
            } else {
                $name = strtolower($request->input('name'));
                $display_name = ucwords(strtolower($request->input('display_name')));
                $description = ucfirst($request->input('description'));

                Role::create([
                    'name' => $name,
                    'display_name' => $display_name,
                    'description' => $description,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);

                DB::commit();

                return response()->json(array('status' => 1, 'message' => 'Successfully created role.', 'intended_url' => '/role'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(array('status' => 0, 'message' => 'Something went wrong.'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        /* RBAC */
        if (! User::authorize('role.edit')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }

        try {
            $data['role'] = Role::findOrFail($id);

            return view('role.edit', $data);
        } catch (Exception $e) {
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /* RBAC */
        if (! User::authorize('role.edit')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'display_name' => 'required',
                'description' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(array('status' => 0, 'message' => $validator->errors()->first()));
            } else {
                $name = strtolower($request->input('name'));
                $display_name = ucwords(strtolower($request->input('display_name')));
                $description = ucfirst($request->input('description'));


                Role::findOrFail($id)->update([
                    'name' => $name,
                    'display_name' => $display_name,
                    'description' => $description,
                    'updated_by' => Auth::user()->id

                ]);

                DB::commit();

                return response()->json(array('status' => 1, 'message' => 'Successfully updated role.', 'intended_url' => '/role'));
            }
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(array('status' => 0, 'message' => 'Something went wrong.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        /* RBAC */
        if (! User::authorize('role.destroy')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        DB::beginTransaction();

        try {
            $post = Role::findOrFail($id);
            $post->delete();

            DB::commit();

            return response()->json(array('status' => 1, 'message' => 'Successfully deleted role.'));
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(array('status' => 0, 'message' => 'Something went wrong.'));
        }
    }

    /**
     * Return datatables data.
     *
     * @return Response
     */
    public function datatable()
    {
        /* RBAC */
        if (! \App\User::authorize('role.index')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        $roles = DB::table('roles')->select(['id','name', 'display_name', 'description', 'created_at', 'updated_at']);

        return Datatables::of($roles)
            ->addColumn('action', function ($role) {
                $buttons = '<div class="text-center"><div class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></button><ul class="dropdown-menu">';

                /* Tambah Action */
                $buttons .= '<li><a href="role/'.$role->id.'/edit"><i class="fa fa-pencil-square-o"></i>&nbsp; Edit</a></li>';
                $buttons .= '<li><a href="javascript:;" data-record-id="'.$role->id.'" onclick="deleteRole($(this));"><i class="fa fa-trash"></i>&nbsp; Delete</a></li>';
                /* Selesai Action */

                $buttons .= '</ul></div></div>';

                return $buttons;
            })
            ->editColumn('created_at', function ($role) {
                return $role->created_at ? with(new Carbon($role->created_at))->format('d F Y H:i') : '';
            })
            ->editColumn('updated_at', function ($role) {
                return $role->updated_at ? with(new Carbon($role->updated_at))->format('d F Y H:i') : '';
            })
            ->make(true);
    }
}
