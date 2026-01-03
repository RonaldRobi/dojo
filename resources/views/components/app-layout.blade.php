@props(['title' => null])

<x-app title="{{ $title }}">
    <x-slot name="header">
        {{ $header ?? '' }}
    </x-slot>

    {{ $slot }}
</x-app>
