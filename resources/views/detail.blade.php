@extends('layouts.app')

@section('content')
<div class="w-4/5 m-auto text-center">
    <div class="py-15 border-b border-gray-200">
        <h1 class="text-6xl">
            Transaction Detail
        </h1>
    </div>
</div>

@if (session()->has('message'))
    <div class="w-4/5 m-auto mt-10 pl-2">
        <p class="w-3/4 text-center" style="background-color: {{
            session()->get('status') ? 'green' : 'red'
        }}; font-size: 20px">
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

<div style="text-align: center;" >
    <table style="
            border-collapse: collapse;
            margin: 25px 0;
            font-size: 0.9em;
            font-family: sans-serif;
            min-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
            margin: auto;
        ">
            <thead>
                <tr style="
                    background-color: #009879;
                    color: #ffffff;
                    text-align: center;
                ">
                    <th style="padding: 12px 15px;">Before Balance</th>
                    <th style="padding: 12px 15px;">Amount</th>
                    <th style="padding: 12px 15px;">Balance</th>
                    <th style="padding: 12px 15px;">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    <tr style="border-bottom: 1px solid #dddddd;">
                        <td style="padding: 12px 15px;">{{ $detail->before_balance }}</td>
                        <td style="padding: 12px 15px;">{{ $detail->amount }}</td>
                        <td style="padding: 12px 15px;">{{ $detail->balance }}</td>
                        <td style="padding: 12px 15px;">{{ date('Y-m-d H:i:s', strtotime($detail->updated_at)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
</div>
<div style="text-align: center">
    <div>
        @foreach ($links as $link)
            @if ($link->url != null)
                <a href="{{ $link->url }}" style="{{ $link->active ? 'font-weight:bold;' : NULL }}">
                    @if($page == $link->label) 
                        <button class="btn btn-primary">
                    @else
                        <button class="btn btn-success">
                    @endif
                        {{ str_replace(['&laquo;', '&raquo;'], '', $link->label) }}
                    </button>
                </a>
            @endif
        @endforeach
    </div>
    <div></div>
    <div>
        <a href="/bank">
            <button class="btn btn-secondary">
                Back to home page
            </button>
        </a>
    </div>
</div>
@endsection