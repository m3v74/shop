<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\UserAccess;
use Illuminate\Http\Request;

class AccessController extends Controller
{
    public function getPermissions(Request $request)
    {
        $user_id = $request->user_id;
        $permissions = UserAccess::where('user_id', $user_id)->first();
        return $permissions;
    }
    public function savePermissions(Request $request)
    {
        $admin = $request->admin;
        $create_category = $request->create_category;
        $delete_category = $request->delete_category;
        $create_product = $request->create_product;
        $delete_product = $request->delete_product;
        $edit_product = $request->edit_product;

        $ua = new UserAccess();

        $ua->admin = $admin;
        $ua->create_category = $create_category;
        $ua->unit_price = $delete_category;
        $ua->create_product = $create_product;
        $ua->delete_product = $delete_product;
        $ua->edit_product = $edit_product;

        $ua->save();
        return true;
    }
}
