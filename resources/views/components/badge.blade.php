{{-- resources/views/components/badge.blade.php --}}
@props(['variant' => 'default'])

@php
$variants = [
    'default' => 'bg-blue-100 text-blue-800',
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'info' => 'bg-gray-100 text-gray-800',
];

$classes = 'text-xs font-medium px-2.5 py-0.5 rounded ' . $variants[$variant];
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>