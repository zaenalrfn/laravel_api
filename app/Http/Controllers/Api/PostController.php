<?php

namespace App\Http\Controllers\Api;

// import model
use App\Models\Post;

// import resource PostResource
use App\Http\Resources\PostResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        // get all data
        $posts = Post::latest()->paginate(5);

        return new PostResource(true, 'Data berhasil diambil', $posts);
    }
}
