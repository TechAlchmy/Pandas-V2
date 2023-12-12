<footer x-data="{ shown: false }" x-intersect.once="shown = true">
    <div class="px-[min(6.99vw,50px)] lg:pl-12 h-[75px] lg:h-[100px]">
        <div class="mx-auto max-w-[1920px]">
            <div class="w-full flex flex-col justify-between items-center">
                <!-- <div class="h-48"></div> -->
                <div x-show="shown" x-transition:enter.duration.1000ms.opactiy class="w-full">
                    <x-hr />
                    <div class="w-full my-5">
                        <div class="flex justify-end gap-x-8">
                            <p class="text-[1rem] animate-fadeIn">Panda People® {{ date('Y') }} © All Rights Reserved</p>
                            <a href="#" class="text-[1rem] hover:text-blue-800">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
