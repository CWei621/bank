@extends('layouts.app')

@section('content')
<div class="w-4/5 m-auto text-center">
    <div class="py-15 border-b border-gray-200">
        <h1 class="text-6xl">
            Account Detail
        </h1>
    </div>
</div>

@if (session()->has('message'))
    <div class="w-4/5 m-auto mt-10 pl-2">
        <p class="w-1/6 mb-4 text-gray-50 bg-green-500 rounded-2xl py-4">
            {{ session()->get('message') }}
        </p>

    </div>
@endif

@if( Auth::check())
    {{-- <div class="pt-15 w-4/5 m-auto">
        <a 
            href="/blog/create"
            class="bg-blue-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
            Create post
        </a>
    </div> --}}
@endif

<div class="sm:grid grid-cols-2 gap-20 w-1/5 mx-auto py-15 border-b border-gray-200 text-center" >
    <div>
        @if (isset(Auth::user()->id) /* && Auth::user()->id == $post->user_id */)
            <h2 class="text-gray-700 font-bold text-5xl pb-4">
                Balance: {{ $balance }}
            </h2>

            <span class="text-gray-500">
                <span class="font-bold italic text-gray-800">
                    {{ $username }}
                </span>
            </span>, Created on {{ date('jS M Y', strtotime('2021-06-01 22:25:00')) }}
        @endif

    </div>

    <div>
        <a href="/" class="uppercase bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8">
            Add Balance
        </a>
    </div>
</div>

@endsection
