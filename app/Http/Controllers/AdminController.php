<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\BinaryOp\Pow;

class AdminController extends Controller
{
    public function ShowPosts()
    {
       
        $post=Post::withCount(['comments','likes'])->with(['categories','user'])->get();
         if($post == [])
        {
            return response()->json([
                'message'=>'No Posts Yet!'
            ],404);
        }else
            return PostResource::collection($post);
    }

    public function deletePost(Post $post)
    {

        $post  ->delete();

        return response()->json([
            'message'=>'Post Deleted!'
        ],200);
        
    }

    public function showUsers(){
        return User::all();
    }

    public function deleteUser(User $user)
    {
        if($user->role == 0)
        {
            $user->posts()->delete();
            $user->delete();
            return response()->json([
                'message'=>'User Deleted!'
            ],200); 
        }else{
            return response()->json([
                'message'=>'Cannot delete an admin'
            ],400); 
        }
    }
}
