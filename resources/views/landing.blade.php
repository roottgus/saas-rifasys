{{-- resources/views/landing.blade.php --}}
@extends('layouts.app')

@section('title', 'Rifasys - Tu Negocio de Rifas Online | Plataforma #1')

@section('content')
    {{-- Hero Section --}}
    @include('sections.hero')
    
    {{-- Logos Carousel --}}
    @include('sections.logos-carousel')
    
    {{-- Features Section --}}
    @include('sections.features')
    
    {{-- How It Works --}}
    @include('sections.how-it-works')
    
    {{-- Stats Section --}}
    @include('sections.stats')
    
    {{-- Pricing Section --}}
    @include('sections.pricing')
    
    {{-- Testimonials --}}
    @include('sections.testimonials')
    
    {{-- CTA Section --}}
    @include('sections.cta')
@endsection