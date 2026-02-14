{{-- resources/views/components/button.blade.php --}}
@props(['type' => 'button', 'variant' => 'primary', 'size' => 'md'])

@php
$baseClasses = 'font-medium rounded-lg focus:ring-4 focus:outline-none transition-colors';

$variants = [
    'primary' => 'text-white bg-blue-700 hover:bg-blue-800 focus:ring-blue-300',
    'secondary' => 'text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 focus:ring-gray-200',
    'success' => 'text-white bg-green-700 hover:bg-green-800 focus:ring-green-300',
    'danger' => 'text-white bg-red-700 hover:bg-red-800 focus:ring-red-300',
];

$sizes = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-5 py-2.5 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>