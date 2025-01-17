@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-10">

            @include('includes.showMessage')

            <div class="card pub_image pub_image_detail">

                <div class="card-header">

                    @if($image->user->image_path)
                    <div class="container-avatar">
                        <img src="{{route('user.avatar',['filename'=>$image->user->image_path])}}" class="avatar" alt="avatar"/>
                    </div>
                    @endif
                    <div class="data-user">
                        {{ $image->user->name.''.$image->user->surname }}
                        <span class="nickname"> 
                            {{'| @'. $image->user->nick}} 
                        </span>
                    </div>
                </div>

                <div class="card-body">

                    <div class="image-container image-detail">
                        <img src="{{ route('image.file',['filename' => $image->image_path]) }}" />
                    </div>

                    <div class="description">
                        <span class="nickname">{{ '@' . $image->user->nick}} </span>
                        <span class="nickname date">{{ ' | ' . \FormatTime::LongTimeFilter($image->created_at)}} </span>                        

                        <p>{{$image->description}}</p>
                    </div>

                    <div class="likes">
                        <!-- Comprobar si el usuario le dio like a la imagen -->

                        <?php $user_like = false; ?>
                        @foreach($image->likes as $like)
                        @if($like->user->id == Auth::user()->id)
                        <?php $user_like = true; ?>
                        @endif
                        @endforeach

                        @if($user_like)
                        <img src="{{asset('\assets\img\heart-red.png')}}" class="btn-dislike" data-id="{{$image->id}}" />
                        @else
                        <img src="{{asset('\assets\img\heart-black.png')}}" class="btn-like"  data-id="{{$image->id}}" />
                        @endif
                        <span class="number-likes"> {{count($image->likes)}} </span>
                    </div>

                    @if(Auth::user() && Auth::user()->id == $image->user->id)

                    <div class="actions">

                        <a href="{{ route('image.edit',['id'=> $image->id]) }}" class="btn btn-sm btn-primary"> Actualizar </a>                        

                        <!-- Button to Open the Modal -->
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#myModal">
                            Borrar Publicacion
                        </button>

                        <!-- The Modal -->
                        <div class="modal" id="myModal">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">¿Estas Seguro?</h4>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <h1>
                                            Si eliminas esta imagen, nunca podras recuperarla.
                                        </h1> 
                                        <h2>
                                            ¿Seguro que quieres borrarla?
                                        </h2>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <a href="{{ route('image.delete',['id' => $image->id]) }}" class="btn btn-danger"> Borrar Definitivamente</a>
                                        <button type="button" class="btn btn-success" data-bs-dismiss="modal">Cerrar</button>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>
                    @endif

                    <div class="clearfix"></div>

                    <div class="comments">

                        <h2>Comentarios {{ count($image->comments) }} </h2>
                        <hr/>

                        <form method="POST" action="{{ route('comment.save') }}">
                            @csrf

                            <input type="hidden" name="image_id" value="{{$image->id}}" />
                            <p>
                                <textarea class="form-control {{ $errors->has('content') ? 'is-invalid': '' }}" name="content" ></textarea>  
                            </p>

                            @if($errors->has('content'))
                            <span class="invalid-feedback" role='alert'>
                                <strong>{{$errors->first('content')}}</strong>
                            </span>
                            @endif

                            <button type="submit" class="btn btn-success">
                                Enviar
                            </button>
                        </form>

                        <hr/>

                        @foreach($image->comments as $comment)                        

                        <div class="comment">

                            <span class="nickname">{{ '@' . $comment->user->nick}} </span>
                            <span class="nickname date">{{ ' | ' . \FormatTime::LongTimeFilter($comment->created_at)}} </span>                        

                            <p>{{$comment->content}}<br/>

                                @if (Auth::check() && ($comment->user_id == Auth::user()->id || $comment->image->user_id == Auth::user()->id))
                                <a href="{{route('comment.delete',['id'=>$comment->id])}}" class="btn btn-sm btn-danger">Eliminar Comentario</a>
                                @endif

                            </p>
                        </div>

                        @endforeach

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
