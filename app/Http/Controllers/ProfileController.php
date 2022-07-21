<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function change_password(Request $request)
    {
        $fields = $request->validate([
            'old_password'=>'required|string',
            'password'=>'required|string|confirmed'
        ]);
        $user=$request->user();
        if(Hash::check($request->old_password,$user->password))
        {
            $user->update([
                'password'=>Hash::make($request->password)
            ]);
            return response()->json([
                "message"=>'Password Successfully changed'
            ],200);
        }else{
            return response()->json([
                "message"=>'Password Does not match'
            ],400);
        }
    }
    public function update_profile(Request $request)
    {
        $fields = $request->validate([
            'name'=>'required|string',
            'profession'=>'nullable|max:100',
            'profile_photo'=>'nullable|image|mimes:jpg,png,bmp'
        ]);
        $user=$request->user();
        if($request->hasFile('profile_photo')){
            if($user->profile_photo){
                $old_path=public_path().'\\uploads\\profile_images\\'.$user->profile_photo;
                if(file_exists($old_path)){
                    File::delete($old_path);
                }
            }
            $image_name=time().'.'.$request->profile_photo->extension();
            $request->profile_photo->move(public_path('\\uploads\\profile_images\\'),$image_name);
        }else{
            $image_name=$user->profile_photo;
        }
        $user->update([
            'name'=>$request->name,
            'profession'=>$request->profession,
            'profile_photo'=>$image_name
        ]);
        return response()->json([
            "message"=>'profile successfully updated',
        ],200);
    }
}
