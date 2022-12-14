<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->only('user_id');
        $posts = Post::with('user')->filter($filter)->get();
        return inertia('Posts/Index', ['posts' => $posts]);
    }

    public function create(Request $request)
    {
        return inertia('Posts/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'text' => 'required|string',
            'media' => ''
        ]);
        $request->user()->posts()->create(
            $data

        );
        return redirect()->route('posts.index');
    }

    public function edit(Request $request, Post $post)
    {
        return inertia('Posts/Create', ['post' => $post]);
    }

    public function update(Request $request, Post $post)
    {
        $user = $request->user() ?? auth('admin')->user();
        if ($post->user_id !== $user->id && !$user->isAdmin) {
            return response(401);
        }

        $data = $request->validate([
            'text' => 'required|string',
            'media' => ''
        ]);
        $post->update($data);
        return redirect()->route('posts.index');
    }

    public function destroy(Request $request, Post $post)
    {
        $user = $request->user() ?? auth('admin')->user();
        if ($post->user_id !== $user->id && !$user->isAdmin) {
            return response(401);
        }
        $post->delete();
        return redirect()->route('posts.index');
    }
}
