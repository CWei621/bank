@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-left">
        <div class="py-15">
            <h1 class="text-6xl">
                Add Balance
            </h1>
        </div>
    </div>

@if ($errors->any())
    <div class="w-4/5 m-auto">
        <ul>
            @foreach($errors->all() as $error)
                <li class="w-1/5 mb-4 text-gray-50 bg-red-700 rounded-2x py-4">
                    {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="w-4/5 m-auto pt-20">
        <form 
            action="/api/bank/add/balance"
            method="POST"
            enctype="multipart/form-data">
            @csrf

        <input
            type="number" 
            name="balance"  
            class="bg-transparent block border-b-2 w-full h-20 text-6xl outline-none">

        <input type="hidden" name="uid" value="{{Auth::user()->id}}" />

        <button 
            type="submit"
            class="uppsercase mt-15 bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl">
            Submit Post
        </button>
        </form>
    </div>
@endsection