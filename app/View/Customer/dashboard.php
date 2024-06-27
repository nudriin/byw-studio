<section class="py-20 bg-nailart-bg" >
        <div class="container mx-auto lg:py-28">
            <div class="w-9/12 p-6 rounded-[20px] mx-auto">
                <div class="grid items-center justify-center grid-cols-12 gap-4">
                    <div class="items-center col-span-6 mx-auto">
                        <img src="<?= getImage("bg/Logo.png")?>" alt="" class="object-cover object-center h-[250px]">
                    </div>
                    <div class="col-span-6">
                        <span class="text-dark-white font-rubik text-[40px] leading-tight">Selamat Datang, kembali!</span><br>
                        <span class="text-ungu font-rubik text-[60px]"><?=$model['customer']->name?></span><br>
                        <span class="text-dark-white font-rubik text-[20px] leading-tight">Your nails speak about your style.
                            Temukan style mu dengan nailist kami</span>
                    </div>
                </div>
            </div>
        </div>
    </section>