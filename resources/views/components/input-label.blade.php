@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-semibold text-coffee-700 mb-1.5']) }}>
    {{ $value ?? $slot }}
</label>
