@props(['tab', 'error' => false])

<div x-show="tab === '{{ $tab }}'" x-cloak>
    {{ $slot }}
</div>