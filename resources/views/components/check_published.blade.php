@if ($status)
    {{-- <svg xmlns="http://www.w3.org/2000/svg"
        class="inline-block h-5 w-5 @if ($successValue === true) text-green-500 @else text-red-500 @endif"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg> --}}
    <svg xmlns="http://www.w3.org/2000/svg"
        class="inline-block h-5 w-5 @if ($successValue === true) text-green-500 @else text-red-500 @endif"
        fill="@if ($successValue === true) #63E6BE @else #e66565 @endif" stroke="currentColor" viewBox="0 0 512 512">
        <path
            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z" />
    </svg>
@else
    {{-- <svg xmlns="http://www.w3.org/2000/svg"
        class="inline-block h-5 w-5 @if ($successValue === false) text-green-500 @else text-red-500 @endif"
        fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
    </svg> --}}
    <svg xmlns="http://www.w3.org/2000/svg"
        class="inline-block h-5 w-5 @if ($successValue === false) text-green-500 @else text-red-500 @endif"
        fill="@if ($successValue === false) #63E6BE @else #e66565 @endif" stroke="currentColor" viewBox="0 0 512 512">
        <path
            d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM175 175c9.4-9.4 24.6-9.4 33.9 0l47 47 47-47c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9l-47 47 47 47c9.4 9.4 9.4 24.6 0 33.9s-24.6 9.4-33.9 0l-47-47-47 47c-9.4 9.4-24.6 9.4-33.9 0s-9.4-24.6 0-33.9l47-47-47-47c-9.4-9.4-9.4-24.6 0-33.9z" />
    </svg>
@endif
