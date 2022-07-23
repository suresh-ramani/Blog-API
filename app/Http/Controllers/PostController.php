<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Comment;
use App\Models\PostLike;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $post=Post::withCount(['comments','likes'])->with(['categories','user'])->get();
        return PostResource::collection($post);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePostRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
           $request->validate([
            'title'=>'required',  
            'excerpt'=>'required',
            'body'=>'required',
        ]);
        $request['slug']=Str::slug($request->title);
        $request['user_id']=Auth::id();
        $input=$request->all();
        $post= Post::create($input);
        if($request->filled('categories')) {

            $categoryIds= $this->createCategories($request->categories); 
            $post->categories()->sync($categoryIds);
       }
       $post->with(['user','Categories'])->get();
        return new PostResource($post);
    }

    public function createCategories(array $categories)
    {
        $ids=[];

        foreach($categories as $category){
            if(is_array($category)){
                $ids[]= $category['id'];
            }else {
                $newCategory = Category::create(['name'=> $category, 'slug'=>Str::slug($category) ]);

                $ids[] = $newCategory->id;
            }
        }
        return $ids;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show($post)
    {   
        $post=Post::where('slug',$post)
        ->withCount(['comments'.'likes'])
        ->with(['user','Categories'])
        ->first();
        return new PostResource($post);   
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePostRequest  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        if (Gate::denies('update-post', $post)) {
            abort(403, "Sorry Not Authorized");
        }

        $input = $request->all();

         if($request->filled('categories')) {

            $categoryIds= $this->createCategories($request->categories);

            $post->categories()->sync($categoryIds);

       }

        $post->update($input);
        $post->withCount(['comments','likes'])->with(['user','Categories'])->get();
        return new PostResource($post);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if (Gate::denies('update-post', $post)) {
            abort(403, "Sorry Not Authorized");
        }

        $post  ->delete();

        return response()->json([
            'message'=>'Post Deleted!'
        ],200);

    }
    /**
     * Search for a name.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function search($name)
    {
        return Post::where('title', 'like', '%'. $name . '%' )
        ->orWhere('body', 'like', '%'. $name . '%' )->get();
    } 
    
    public function toggle_like(UpdatePostRequest $request,$post)
    {
        $post=Post::where('slug',$post)->first();
        if($post){
            $user= $request->user();
            $post_like=postLike::where('post_id',$post->id)
            ->where('user_id',Auth::id())->first();
            if($post_like){
                $post_like->delete();
                return response()->json([
                    'message'=>'Like successfullt removed'
                ],200);
            }else{
                PostLike::create([
                    'post_id'=>$post->id,
                    'user_id'=>Auth::id()
                ]);
                return response()->json([
                    'message'=>'Like successfullt added'
                ],200);
            }

        }else{
            return response()->json([
                'message'=>'no posts found'
            ],400);
        }  
    }

}
