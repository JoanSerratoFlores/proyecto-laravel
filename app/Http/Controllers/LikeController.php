<?php

namespace App\Http\Controllers;

use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function like($image_id) {

        //Recoger datos del usuario y la imagen

        $user = \Auth::user();

        //Condicion para ver si ya existe el like y no duplicarlo
        $isset_like = Like::where('user_id', $user->id)
                    ->where('image_id', $image_id)
                    ->count();

        if ($isset_like == 0) {

            $like = new Like();
            $like->user_id = $user->id;
            $like->image_id = (int)$image_id;

            //Guardar
            $like->save();
            
            return response()->json([
                'like'=>$like
            ]);
            
            
        } else {

            return response()->json([
                'message'=>'El like ya existe'
            ]);
            
        }
    }

    public function dislike($image_id) {
        
        //Recoger datos del usuario y la imagen

        $user = \Auth::user();

        //Condicion para ver si ya existe el like y no duplicarlo
        $like = Like::where('user_id', $user->id)
                ->where('image_id', $image_id)
                ->first();

        if ($like) {

            //Eliminar Like
            $like->delete();
            
            return response()->json([
                'like'=>$like,
                'message'=>'Has dado dislike correctamente',
            ]);
            
            
        } else {

            return response()->json([
                'message'=>'El like no existe'
            ]);
            
        }
        
    }
    
    public function index(){
        
        $user = \Auth::user();
        $likes = Like::where('user_id',$user->id)
                ->orderBy('id','desc')
                ->paginate(5);
        
        return view('likes.index',[
            'likes'=>$likes,
        ]);
        
    }
}
