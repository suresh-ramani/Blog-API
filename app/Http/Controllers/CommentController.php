<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{

     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        
        $post=Post::where('slug',$slug)->first();
        if($post){
            $id=$post->id;
            $comments=Comment::with(['user'])->where('post_id',$id)->orderBy('id','desc')->get();
            return new  CommentResource($comments);
        }
        else{
            return response()->json([
                'message'=>'no post found'
            ],400);
        }
        
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCommentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCommentRequest $request,$slug)
    {
        $post=Post::where('slug',$slug)->first();
        if($post){
            $request->validate([
                'comment'=>'required'
            ]);
            $request['post_id']=$post->id;
            $request['user_id']=Auth::id();
            $input=$request->all();
            $comment= Comment::create($input);
            $comment->load('user');
            return new  CommentResource($comment);
        }else{
            return response()->json([
                'message'=>'no post found'
            ],400);
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCommentRequest $request, $comment_id)
    {
        
        $comment=Comment::with(['user'])->where('id',$comment_id)->first();
        if($comment){
            if($comment->user_id==$request->user()->id)
            {
                $request->validate([
                    'comment'=>'required'
                ]);
                $input=$request->all();
                $comment->update([
                    'comment'=>$request->comment
                ]);
                return new  CommentResource($comment);
            }
            else{
                return response()->json([
                    'message'=>'not Authorized'
                ],403);
            }
            
            return new  CommentResource($comment);
        }else{
            return response()->json([
                'message'=>'no comments found'
            ],400);
        }   
    }

    /**
     * Remove the specified resource from storage.
     * @param  \App\Http\Requests\UpdateCommentRequest  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(UpdateCommentRequest $request,$comment_id)
    { 
        $comment=Comment::where('id',$comment_id)->first();
        if($comment){
        if($comment->user_id==$request->user()->id)
            {
                $comment->delete();
                return response(['message' => 'comment deleted!']);
            }else{
                return response()->json([
                        'message'=>'not Authorized'
                ],403);
            }
        }else{
            return response()->json([
                'message'=>'no comments found'
            ],400);
        }  
        
    }
}
