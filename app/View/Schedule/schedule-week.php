<section class="py-20 bg-nailart-bg">
    <div class="container w-9/12 px-4 py-4 mx-auto lg:px-20 lg:py-28">
        <div class="flex flex-col items-center justify-center w-9/12 mx-auto">
            <h1 class="text-dark-white font-rubik text-[50px] text-center">
                Jadwal minggu ini
            </h1>
            <div class="bg-ungu font-rubik rounded-[15px] p-2">
                <a href="/admin/add-schedule">Tambah Jadwal</a>
            </div>
        </div>
        <?php if (isset($model['schedule']) && $model['schedule'] != null && !isset($model['error'])) { ?>
            <div class="flex flex-wrap items-center justify-center">
                <!-- card -->
                <?php foreach ($model['schedule'] as $row) { ?>
                    <div class="p-4 space-y-2 w-60">
                        <div class="h-full rounded-[20px] text-ungu overflow-hidden hover:shadow-lg">
                            <a href="/admin/schedule/<?= $row['date'] ?>">
                                <div class="p-6 bg-abu hover:shadow-lg">
                                    <h1 class="text-center font-rubik"><?= $row['date'] ?></h1>
                                </div>
                            </a>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <div class="w-full mx-auto mt-12 lg:w-9/12">
                <div class="h-[200px]">
                    <div x-show="open" class="flex flex-row text-center bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-center mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <h1 class="text-lg text-center text-white"><?= $model['error'] ?></h1>
                        <!-- <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button> -->
                    </div>
                </div>
            </div>
        <?php } ?>
</section>