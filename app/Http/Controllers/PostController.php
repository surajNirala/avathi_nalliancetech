<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function __construct() {
        $this->middleware('auth:api');
    }

    public function postlist()
    {
        $posts = Post::with(['categories','tags'])->get();
        if(count($posts) > 0){
            $postArr = [];
            foreach ($posts as $key => $value) {
               $postArr[] = [
                   'title'           => $value->title,
                   'slug'            => $value->slug,
                   'description'     => $value->description,
                   'total_cat_count' => $value->categories->count(),
                   'total_tag_count' => $value->tags->count()
               ];
            }
            return response()->json([
                'message' => 'post list.',
                'post'    => $postArr
            ], 500);
        }
        return response()->json([
            'message' => 'Data not found.',
        ], 200);
    }

    public function postdetails(Request $request)
    {   
        $req = Validator::make($request->all(), [
            'post_id' => 'required|numeric|gt:0',
        ]);
    
        if($req->fails()){
            return response()->json($req->errors(), 422);
        }

        $post = Post::with(['categories','tags'])->where('id',$request->post_id)->first();
        if(!empty($post)){
            return response()->json([
                'message' => 'post details.',
                'postdetails'    => $post
            ], 500);
        }
        return response()->json([
            'message' => 'Data not found.',
        ], 200);
    }
   public function create(Request $request)
   {
    $req = Validator::make($request->all(), [
        'title' => 'required|string|unique:post',
        'slug'  => 'required|string|unique:post',
        'description' => 'required',
        'categories' => 'required|array',
        'tags' => 'required|array',
    ]);

    if($req->fails()){
        return response()->json($req->errors(), 422);
    }
    $postArr = [
        'title'        => $request->title,
        'slug'         => $request->slug,
        'description'  => $request->description,
    ];
    $post = Post::create($postArr);
    if($post){
        foreach ($request->categories as $key => $value) {
            $catArr = [
                'title'   => $value,
                'post_id' => $post->id,
            ];
            Category::create($catArr);
        }
        foreach ($request->tags as $key => $value) {
            $tagArr = [
                'title'   => $value,
                'post_id' => $post->id,
            ];
            Tag::create($tagArr);
        }
        return response()->json([
            'message' => 'Post created successfully.',
            'post' => $post
        ], 201);
    }
    return response()->json([
        'message' => 'Something wrong.',
    ], 500);
   }
}
