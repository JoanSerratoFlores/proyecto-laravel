@extends('layouts.app')

@section('content')
<div class="container">

    <div class="row justify-content-center">

        <div class="col-md-8">

            <h1>Gente</h1>

            <form method='GET' action="{{ route('user.index') }}" id="buscador">

                <div class="row">

                    <div class="form-group col">

                        <input type="text" id="search" class="form-control"/>

                    </div>

                    <div class="form-group col btn-search">

                        <input type="submit" value="Buscar" class="btn btn-success"/>

                    </div>

                </div>

            </form>

            <hr/>

            @foreach($users as $user)

            <div class="profile-user">

                @if($user->image_path)

                <div class="container-avatar">
                    <img src="{{route('user.avatar',['filename'=>$user->image_path])}}" class="avatar" alt="avatar"/>
                </div>

                @endif

                <div class="user-info">
                    <h2> {{'@'.$user->nick}} </h2>
                    <h3> {{$user->name . ' ' . $user->surname}} </h3>
                    <p> {{ 'Se unió: '.\FormatTime::LongTimeFilter($user->created_at)}} </p>
                    <a href="{{ route('profile', ['id'=>$user->id]) }}" class="btn btn-success">Ver Perfil</a>
                </div>

                <div class="clearfix"></div>

                <hr/>

            </div>

            @endforeach

            <!-- PAGINACION -->

            <div class="clearfix"></div>
            {{$users->links()}}
        </div>

    </div>
</div>

@endsection
