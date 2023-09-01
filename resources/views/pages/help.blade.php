<?php
use function Laravel\Folio\{name};

name('help');
?>
<x-layouts.app>
    <div class="grid grid-cols-1 md:grid-cols-2">
        <div>
            <div class="relative min-h-screen max-h-[1080px]"
                style="background-image: url(https://images.unsplash.com/photo-1648832328633-89b993c5d6b7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=987&q=80)">
                <div class="absolute inset-0 p-8 text-white flex flex-col justify-between">
                    <h1 class="text-6xl">Help</h1>
                    <p>
                        Panda People supplies benefits that are offered through your employer. If you’re not finding what you’re looking for on your My Benefits page, please reach out to your employer.

                        For anything else you need help with on your Panda Portal, such as help with checking out or finding deals, search our FAQ or get in touch with us.
                    </p>
                </div>
            </div>
        </div>
        <div class="p-8 space-y-4">
            <h2 class="text-6xl font-light">Frequently Asked Questions</h2>
            <ul class="divide-y" x-data="{ selected: null }">
                @foreach (range(1, 6) as $question)
                    <li class="py-6 space-y-4">
                        <div>
                            <button class="text-xl" x-on:click="selected = (selected == {{ $loop->index }}) ? null : {{ $loop->index }}">Question</button>
                        </div>
                        <p class="" x-show="selected == {{ $loop->index }}" x-cloak x-transition>
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris pellentesque mi ac est ultrices sodales. Maecenas nec libero ut nibh euismod pharetra. Morbi diam lacus, sollicitudin quis est
                            in, molestie posuere augue. Vestibulum at lacus id erat posuere dignissim. Ut convallis nisl ex. Nam ornare porttitor mi, a eleifend lectus rutrum vel. Cras luctus, dui vel ullamcorper
                            aliquam, tellus mauris efficitur nunc, at dapibus orci nunc id mauris.

                            Mauris eleifend nisi est, ac malesuada lorem posuere vitae. Sed dictum auctor felis, id ornare est bibendum vitae. Praesent vehicula aliquam dolor in consequat. In tempus finibus molestie. Ut
                            id lacinia arcu. Cras imperdiet dapibus massa, ut maximus velit hendrerit eget. Morbi eget sem quis velit hendrerit sagittis quis a elit. Phasellus rhoncus porta quam, non rutrum lacus dapibus
                            in. Donec urna sem, tincidunt vitae ex sit amet, finibus bibendum est. Duis molestie massa id arcu accumsan, ut volutpat massa fringilla. Nunc tristique feugiat ligula eu tincidunt. Duis id
                            maximus risus. Integer pulvinar ornare nulla, at maximus est tincidunt vestibulum. Proin vitae molestie mauris, sed congue turpis.

                            Mauris eu fringilla ipsum, vitae pulvinar odio. Proin sed mi sit amet sapien hendrerit tempus. Integer quis elit ac risus vestibulum iaculis. Fusce pretium lorem non vestibulum ultricies. Sed
                            nibh lectus, volutpat in justo a, scelerisque gravida felis. Curabitur venenatis, ipsum et gravida vehicula, nunc ex euismod ligula, non pretium risus sapien vitae magna. Cras quis lectus
                            enim. Mauris laoreet tellus quis odio vehicula feugiat.

                            Nullam et lorem tempor, faucibus mi at, ultrices mauris. Praesent sodales ultricies nunc vel mollis. Aliquam erat volutpat. Nullam augue ante, dapibus sed lorem nec, semper euismod tellus.
                            Suspendisse ultrices nisl sed massa lacinia bibendum. Sed blandit accumsan erat non viverra. Integer suscipit metus a quam pulvinar ultrices. Sed at mi vel mauris aliquet rhoncus. Donec
                            ultrices arcu ut suscipit pharetra. Nunc mattis leo in facilisis ullamcorper. Vestibulum bibendum mauris a eros sodales malesuada. Cras dapibus diam quis est elementum, ut porttitor orci
                            feugiat. Suspendisse venenatis tincidunt tincidunt. Nunc ullamcorper felis in ligula tristique interdum. Nunc vehicula metus blandit tincidunt tincidunt.
                        </p>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</x-layouts.app>
