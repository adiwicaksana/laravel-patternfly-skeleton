<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use Carbon\Carbon;
use Datatables;
use DB;
use Validator;
use Hash;
use Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* RBAC */
        if (! User::authorize('user.index')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }

        return view('user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /* RBAC */
        if (! \App\User::authorize('user.create')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }

        $data['roles'] = Role::all();

        return view('user.create', $data);
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
        if (! \App\User::authorize('user.create')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required',
                'username' => 'required',
                'email' => 'required|email',
                'role_id' => 'required',
                'password' => 'required',
                'is_suspended' => 'required',
                'is_disabled' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(array('status' => 0, 'message' => $validator->errors()->first()));
            } else {
                $full_name = ucwords(strtolower($request->input('full_name')));
                $username = $request->input('username');
                $email = $request->input('email');
                $role_id = $request->input('role_id');
                $password = Hash::make($request->input('password'));
                $is_suspended = $request->input('is_suspended');
                $is_disabled = $request->input('is_disabled');

                $user = User::create([
                    'full_name' => $full_name,
                    'username' => $username,
                    'email' => $email,
                    'role_id' => $role_id,
                    'password' => $password,
                    'is_suspended' => $is_suspended,
                    'is_disabled' => $is_disabled,
                    'created_by' => Auth::user()->id,
                    'updated_by' => Auth::user()->id
                ]);

                DB::commit();

                return response()->json(array('status' => 1, 'message' => 'Successfully created user.', 'intended_url' => '/user'));
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
        if (! \App\User::authorize('user.edit')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }


        try {
            $data['roles'] = Role::all();
            $data['user'] = User::findOrFail($id);

            return view('user.edit', $data);
        }
        catch (Exception $e) {

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
        if (! \App\User::authorize('user.edit')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        DB::beginTransaction();

        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required',
                'email' => 'required|email',
                'role_id' => 'required',
                'is_suspended' => 'required',
                'is_disabled' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(array('status' => 0, 'message' => $validator->errors()->first()));
            } else {
                $full_name = ucwords(strtolower($request->input('full_name')));
                $email = $request->input('email');
                $role_id = $request->input('role_id');
                $is_suspended = $request->input('is_suspended');
                $is_disabled = $request->input('is_disabled');

                User::findOrFail($id)->update([
                    'full_name' => $full_name,
                    'email' => $email,
                    'role_id' => $role_id,
                    'is_suspended' => $is_suspended,
                    'is_disabled' => $is_disabled,
                    'updated_by' => Auth::user()->id
                ]);

                DB::commit();

                return response()->json(array('status' => 1, 'message' => 'Successfully updated user.', 'intended_url' => '/user'));
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
        if (! \App\User::authorize('user.destroy')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        DB::beginTransaction();

        try {
            $post = User::findOrFail($id);
            $post->delete();

            DB::commit();

            return response()->json(array('status' => 1, 'message' => 'Successfully deleted user.'));
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json(array('status' => 0, 'message' => 'Something went wrong.'));
        }
    }

    public function datatable(){
        $users = DB::table('users')->select(['users.id','full_name', 'username', 'email', 'roles.display_name as role_name', 'password', 'is_suspended', 'is_disabled', 'users.created_at', 'users.updated_at'])
            ->join('roles', 'roles.id', '=', 'users.role_id');

        return Datatables::of($users)
            ->addColumn('action', function ($user) {
                $buttons = '<div class="text-center"><div class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true"><i class="fa fa-bars"></i></button><ul class="dropdown-menu">';

                /* Tambah Action */
                $buttons .= '<li><a href="user/'.$user->id.'/edit"><i class="fa fa-pencil-square-o"></i>&nbsp; Edit</a></li>';
                $buttons .= '<li><a href="javascript:;" data-record-id="'.$user->id.'" onclick="deleteUser($(this));"><i class="fa fa-trash"></i>&nbsp; Delete</a></li>';
                /* Selesai Action */

                $buttons .= '</ul></div></div>';

                return $buttons;
            })
            ->editColumn('created_at', function ($user) {
                return $user->created_at ? with(new Carbon($user->created_at))->format('d F Y H:i') : '';
            })
            ->editColumn('updated_at', function ($user) {
                return $user->updated_at ? with(new Carbon($user->updated_at))->format('d F Y H:i') : '';
            })
//            ->editColumn('is_suspended', function ($user) {
//                return ($user->is_suspended == "1") ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
//            })
//            ->editColumn('is_disabled', function ($user) {
//                return ($user->is_disabled == "1") ? '<span class="label label-success">Yes</span>' : '<span class="label label-danger">No</span>';
//            })
            ->make(true);
    }
}
