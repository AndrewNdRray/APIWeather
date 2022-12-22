@extends('layouts.app')

@section('content')
<body class="antialiased">
<div class="row">
    <div class="col-md-12">
        <div class="box-body">
            <strong><i class="fa fa-book margin-r-5"></i> {{ Auth::user()->name }} </strong>

            <p class="text-muted">
                ID is # {{ Auth::user()->id }}
            </p>

            <hr>
            <strong><i class="fa fa-pencil margin-r-5"></i> User email </strong>
            <p>
                {{ Auth::user()->email }}
            </p>
            <hr>

@endsection

