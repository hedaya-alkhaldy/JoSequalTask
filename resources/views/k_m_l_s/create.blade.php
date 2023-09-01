@extends('layouts.app')

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>
                    Create K M L S
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <div class="content px-3">
        @include('flash::message')

        @include('adminlte-templates::common.errors')

        <div class="card">

            {!! Form::open(['route' => 'kmls.store','files'=>true,'enctype'=>'multipart/form-data']) !!}

            <div class="card-body">

                <div class="row">
                    @include('k_m_l_s.map')
                </div>

            </div>

            <div class="card-footer">

                <a href="{{ route('kmls.index') }}" class="btn btn-default"> Cancel </a>
            </div>

            {!! Form::close() !!}

        </div>
    </div>



@endsection



