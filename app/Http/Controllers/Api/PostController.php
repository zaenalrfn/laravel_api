<?php

namespace App\Http\Controllers\Api;

// import model
use App\Models\Post;

// import resource PostResource
use App\Http\Resources\PostResource;

use App\Http\Controllers\Controller;
//import facade Validator
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // get all data
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'Data berhasil diambil', $posts);
    }

    public function store(Request $request)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'image'     => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'title'     => 'required',
            'content'   => 'required',
        ]);

        // check validator
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()
            ], 401);
        }
        // upload image
        $image = $request->file('image');
        $image->storeAs('public/images', $image->hashName());

        // create data
        $post = Post::create([
            'image'     => $image->hashName(),
            'title'     => $request->title,
            'content'   => $request->content,
        ]);

        return new PostResource(true, 'Data berhasil ditambahkan', $post);
    }
}
