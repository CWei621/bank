@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-center">
        <div class="py-15">
            <h1 class="text-6xl">
                Add Balance
            </h1>
        </div>
    </div>

@if (Auth::check())
<div class="w-4/5 m-auto pt-20 text-center">
    <div>
        <form
            action="/bank/balance"
            method="POST"
            enctype="multipart/form-data">
            @csrf

        <input
            type="number"
            name="balance"
            class="bg-transparent border-b-2 h-20 outline-none" />
        <input type="hidden" name="user_id" value="{{Auth::user()->id}}" />
            <button class='btn btn-primary'>
                Add Balance
            </button>
        </form>
    </div>

    <a href="/bank">
        <button class="btn btn-secondary mt-2">
            Back to home page
        </button>
    </a>
</div>
@endif


@endsection
