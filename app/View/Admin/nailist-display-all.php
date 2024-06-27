<section class="py-20 bg-nailart-bg">
    <div class="container w-9/12 px-4 py-4 mx-auto lg:px-20 lg:py-28">
        <div class="flex flex-col items-center justify-center w-9/12 mx-auto">
            <h1 class="text-dark-white font-rubik text-[50px] text-center">
                Daftar Nailist
            </h1>
            <div class="">
                <a href="/admin/add-nailist" class="p-2 rounded-lg bg-ungu font-rubik">Tambah nailist</a>
            </div>
        </div>
        <?php if (isset($model['nailist']) && $model['nailist'] != null && !isset($model['error'])) { ?>
            <div class="flex flex-wrap justify-center mt-4 -m-4 lg:gap-x-20">
                <!-- card -->
                <?php foreach ($model['nailist'] as $row) { ?>
                    <div class="p-4 w-full md:w-[238px] space-y-2">
                        <div class="h-full rounded-[20px] overflow-hidden shadow-lg">
                            <img src="<?= getUploadedImg("nailist/$row[picture]") ?>" alt="" class="object-cover object-center w-full h-[250px]">
                            <a href="/admin/update-nailist/<?= $row['id'] ?>">
                                <div class="p-6 transition duration-200 ease-in text-dark-white hover:bg-abu hover:text-ungu">
                                    <h1 class="text-center font-rubik "><?= $row['name'] ?></h1>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="w-full mx-auto lg:w-9/12">
                <div x-show="open" class="flex flex-row bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-between mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                    <h1 class="text-lg text-white"><?= $model['error'] ?></h1>
                    <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button>
                </div>
            </div>
        <?php } ?>
</section>