<button {{ $attributes->merge(['type' => 'button', 'class' => 
'font-aeonik panda-submit inline-flex items-center px-4 py-2 bg-white  disabled:opacity-25 transition ease-in-out 
duration-150']) }}>
    {{ $slot }}
</button>
