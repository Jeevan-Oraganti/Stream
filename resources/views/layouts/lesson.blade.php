@extends('layouts.master')

@section('content')
    <div class="example">
        <div class="flex md:justify-center">

            <div class="bg-grey rounded-lg p-4 mx-4">
                <count :to="11"></count>
            </div>

            <div class="bg-grey rounded-lg p-4 mx-4">
                <count :to="5"></count>
            </div>

            <div class="bg-grey rounded-lg p-4 mx-4">
                <count :to="1955"></count>
            </div>

        </div>
    </div>
@endsection
