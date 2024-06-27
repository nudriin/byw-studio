<section class="py-20 bg-nailart-bg" x-data="{open : true}">
    <div class="container mx-auto lg:py-28">
        <?php if (isset($model['error'])) { ?>
            <div class="w-full mx-auto lg:w-9/12">
                <div x-show="open" class="flex flex-row bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-between mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                    <h1 class="text-lg text-white"><?= $model['error'] ?></h1>
                    <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button>
                </div>
            </div>
        <?php } ?>
        <div class="w-9/12 bg-white shadow-md p-6 rounded-[20px] mx-auto">
            <div class="grid items-center justify-center grid-cols-12">
                <!-- <div class="col-span-6">
                        <img src="/public/Template/img/HeroImg.png" alt="">
                    </div> -->
                <div class="col-span-12">
                    <div class="flex items-center justify-center">
                        <h1 class="mb-4 text-4xl font-rubik">Tambah Jadwal</h1>
                    </div>
                    <form action="/admin/add-schedule" method="post" class="col-span-6">
                        <div class="flex flex-col items-center justify-center gap-3">
                            <div>
                                <label for="" class="text-abu font-rubik">Tanggal</label>
                                <input type="date" name="dates" id="" placeholder="Tanggal" class="w-full p-2 border-2 rounded-lg border-abu">
                            </div>
                            <div>
                                <label for="" class="text-abu font-rubik">Jam</label>
                                <input type="time" name="book_time" id="" placeholder="Jam" class="w-full p-2 border-2 rounded-lg border-abu ">
                            </div>
                            <div>
                                <button type="submit" class="w-full p-2 bg-abu rounded-[15px] text-ungu font-rubik">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- <div class="bg-dark-white rounded-[20px] shadow-lg"> -->
            <!-- </div> -->
        </div>
    </div>
</section>