<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datatables;
use Carbon\Carbon;
use App\User;
use DB;
use Validator;
use Hash;
use Auth;

class StoreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        /* RBAC */
        if (! User::authorize('store.index')) {
            flash('Insufficient permission', 'warning');
            return redirect('home');
        }

        return view('store.index');
    }

    public function datatable(){
        /* RBAC */
        if (! \App\User::authorize('store.index')) {
            return response()->json(array('status' => 0, 'message' => 'Insufficient permission.'));
        }

        $stores = DB::table('stores')->select('*')->where('store_status', '=', 1);

        return Datatables::of($stores)
            ->editColumn('created_at', function ($store) {
                return $store->created_at ? with(new Carbon($store->created_at))->format('d F Y H:i') : '';
            })
            ->editColumn('updated_at', function ($store) {
                return $store->updated_at ? with(new Carbon($store->updated_at))->format('d F Y H:i') : '';
            })
            ->make(true);
    }
}
