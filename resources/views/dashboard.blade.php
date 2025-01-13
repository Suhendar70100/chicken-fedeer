@extends('layout')
@section('content')
<div id="bg">
    <div class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="text-center">
            <h1 style="color: white;">Welcome to DOCFeeder!</h1>
            <a href="/homepage"><button class="btn btn-primary mt-4" type="button" id="gtHome">Go to Home Page</button></a>
        </div>
    </div>
</div>
@endsection

<style>
    #bg{
        background: linear-gradient(rgba(43, 43, 43, 0.5), rgba(43, 43, 43, 0.5)),
                    url('https://plus.unsplash.com/premium_photo-1661963063875-7f131e02bf75?q=80&w=2070&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D');
        background-size: 100% 920px; 
        overflow: hidden; 
        background-repeat: no-repeat; 
        position: absolute; 
        top: 0; 
        left:0; 
        right: 0;
    }

    #gtHome:hover{
        transform: scale(1.1);
        transition: 0.3s ease-in-out;
    }

    #gtHome:not(:hover){
        transform: scale(1.0);
        transition: 0.3s ease-in-out;
    }
</style>
