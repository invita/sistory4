<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use \Illuminate\Http\Request;

class ApiController extends Controller
{
    public function entityList(Request $request)
    {


        return ["status" => true, "data" => ["foo" => "bar"], "error" =>  null];
    }
}