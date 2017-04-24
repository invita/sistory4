<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;

class LogoutController extends Controller
{
    public function index(Request $request)
    {
        \Auth::logout();
        return redirect()->route("admin.index.index#get");
    }
}