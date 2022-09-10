<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function posts(Request $request)
    {
        $posts = $request->user()->posts;
        return inertia('User/Posts', ['posts' => $posts]);
    }
}
