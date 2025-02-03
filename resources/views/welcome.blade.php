<!-- resources/views/welcome.blade.php -->
@extends('layouts.master')

@section('content')
<div class="container mx-auto py-8">
    <h1 class="text-4xl font-bold mb-6 text-center">Welcome to My App</h1>
    <p class="text-lg text-center mb-4">This is the entry point of the website.</p>
    <div id="app">
        <welcome-page></welcome-page>
    </div>
</div>
@endsection