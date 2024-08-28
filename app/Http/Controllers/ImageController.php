<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use App\Models\Comment;
use App\Models\Like;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;



class ImageController extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    public function create() {

        return view('image.create');
    }

    public function save(Request $request) {

        //Validacion

        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'required|image',
        ]);

        //Recoger Datos
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //Asignar valores al objeto
        $user = \Auth::user();
        $image = new Image();
        $image->user_id = $user->id;
        $image->description = $description;

        //Subir Fichero
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            Storage::disk('images')->put($image_path_name, File::get($image_path));
            
            $image->image_path = $image_path_name;
        }

        $image->save();

        return redirect()->route('home')
                        ->with(['message' => 'La foto ha sudo subida correctamente']);
    }

    public function getImage($filename) {

        $file = Storage::disk('images')->get($filename);
        return new Response($file, 200);
    }

    public function detail($id) {

        $image = Image::find($id);

        return view('image.detail', [
            'image' => $image
        ]);
    }

    public function delete($id) {

        $user = \Auth::user();
        $image = Image::find($id);
        $comments = Comment::where('image_id', $id)->get();
        $likes = Like::where('image_id', $id)->get();

        if ($user && $image && $image->user->id == $user->id) {

            //Eliminar comentarios

            if ($comments && count($comments) >= 1) {
                foreach ($comments as $comment) {

                    $comment->delete();
                }
            }

            //Eliminar los likes

            if ($likes && count($likes) >= 1) {
                foreach ($likes as $like) {

                    $like->delete();
                }
            }

            //Eliminar ficheros de imagen

            Storage::disk('images')->delete($image->image_path);

            //Eliminar reistro imagen

            $image->delete();

            $message = array('message' => 'La imagen se ha borrado ');
        } else {

            $message = array('message' => 'La imagen no se ha borrado ');
        }

        return redirect()->route('home')->with($message);
    }

    public function edit($id) {

        $user = \Auth::user();
        $image = Image::find($id);

        if ($user && $image && $image->user->id == $user->id) {

            return view('image.edit', [
                'image' => $image,
            ]);
        } else {

            return redirect()->route('home');
        }
    }

    public function update(request $request) {

        //Validacion

        $validate = $this->validate($request, [
            'description' => 'required',
            'image_path' => 'image',
        ]);

        //Recoger Datos
        $image_id = $request->input('image_id');
        $image_path = $request->file('image_path');
        $description = $request->input('description');

        //Conseguir objeto image

        $image = Image::find($image_id);
        $image->description = $description;

        //Subir Fichero
        if ($image_path) {
            $image_path_name = time() . $image_path->getClientOriginalName();
            \Illuminate\Support\Facades\Storage::disk('images')->put($image_path_name, \Illuminate\Support\Facades\File::get($image_path));
            $image->image_path = $image_path_name;
        }

        //Actualizar Registro
        $image->update();
        return redirect()->route('image.detail', ['id' => $image_id])
                ->with(['message'=>'Imagen Actualizada con Exito']);
    }
}
