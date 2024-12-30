<?php

namespace App\Http\Controllers\Api;

// import model
use App\Models\Post;

// import resource PostResource
use App\Http\Resources\PostResource;

use App\Http\Controllers\Controller;
//import facade Validator
use Illuminate\Support\Facades\Validator;
//import facade Storage
use Illuminate\Support\Facades\Storage;
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

    public function show($id)
    {
        try {
            // find post by ID, this will throw ModelNotFoundException if not found
            $post = Post::findOrFail($id);

            return new PostResource(true, 'Data detail berhasil diambil', $post);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // validate data
        $validator = Validator::make($request->all(), [
            'image'     => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        try {
            // find post by ID, this will throw ModelNotFoundException if not found
            $post = Post::findOrFail($id);

            // check if image is uploaded
            if ($request->hasFile('image')) {
                // upload new image
                $image = $request->file('image');
                $image->storeAs('public/images', $image->hashName());

                // delete old image
                Storage::delete('public/images/' . basename($post->image));

                // update post with new image
                $post->update([
                    'image'     => $image->hashName(),
                    'title'     => $request->title,
                    'content'   => $request->content,
                ]);
            } else {
                // update post without image
                $post->update([
                    'title'     => $request->title,
                    'content'   => $request->content,
                ]);
            }

            return new PostResource(true, 'Data posts berhasil diubah', $post);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Post not found'
            ], 404);
        }
    }
}
