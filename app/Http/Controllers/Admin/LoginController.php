<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        if($request->isMethod("post")){
            $this->validate($request, [
                "name" => "required",
                "password" => "required"
            ]);

            if(\Auth::attempt(["name" => $request->input("name"), "password" => $request->input("password")],
                $request->has("remember"))) {
                return redirect()->intended(route("admin.index.index#get"));
            }

            return view("admin.login.index")->withErrors("Wrong username or password");
        }

        return view("admin.login.index");
    }
}