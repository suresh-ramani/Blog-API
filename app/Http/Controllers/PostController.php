<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Category;
use App\Models\Comment;
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
        
        $post=Post::withCount('comments')->with(['categories','user'])->get();
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

        return new PostResource($post->withCount('comments')->with(['user','Categories'])->get());
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
    public function show(Post $post)
    {
      return new PostResource($post->withCount('comments')->with(['user','Categories'])->get());   
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

        return new PostResource($post->withCount('comments')->with(['user','Categories'])->get());
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

        return response(['message' => 'blog deleted!']);

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

}
