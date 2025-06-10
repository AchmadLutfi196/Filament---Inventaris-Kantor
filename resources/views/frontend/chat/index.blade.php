@extends('frontend.layouts.app')

@section('title', 'Chat dengan Admin')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Chat dengan Admin</h1>
        <p class="mt-2 text-gray-600">Hubungi admin untuk bantuan terkait inventaris kantor</p>
    </div>

    <div class="bg-white rounded-lg shadow-lg overflow-hidden" style="height: 600px;">
        @livewire('chat-room', ['chatId' => $chat->id])
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
@endpush
@endsection
