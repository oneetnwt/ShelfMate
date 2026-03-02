@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-stone-200 bg-stone-50 text-stone-800 placeholder-stone-400 focus:border-amber-400 focus:ring-amber-400 rounded-lg shadow-sm w-full disabled:opacity-50 disabled:cursor-not-allowed']) }}>