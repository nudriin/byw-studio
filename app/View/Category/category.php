<section class="py-20 bg-nailart-bg">
    <div class="container mx-auto lg:py-28">
        <div class="w-9/12 mx-auto ">
            <div class="flex items-center justify-end w-9/12 gap-2 mx-auto mb-2">
                <div class="font-rubik bg-ungu p-2 rounded-[15px] hover:shadow-md">
                    <a href="/admin/add-category">Tambah Kategori</a>
                </div>
            </div>
            <?php if (isset($model['category']) && $model['category'] != null && !isset($model['error'])) { ?>
                <div class="w-9/12 mx-auto rounded-[20px] overflow-hidden">
                    <table class="w-full font-rubik">
                        <thead class="border-b">
                            <tr class="bg-abu text-ungu">
                                <th class="py-4">
                                    Nomor
                                </th>
                                <th class="py-4">
                                    Nama Category
                                </th>
                                <th class="py-4">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">

                            <?php
                            $i = 0;
                            foreach ($model['category'] as $row) {
                                $i++; ?>
                                <tr class="border-b">
                                    <td class="py-2 text-center bg-white"><?= $i ?></td>
                                    <td class="py-2 text-center bg-white"><?= $row['name'] ?></td>
                                    <td class="py-2 text-center bg-white">
                                        <a href="/admin/update-category/<?= $row['id'] ?>" class="text-biru">Edit</a>
                                        <a onclick="return confirm('anda yakin ingin menghapus <?= $row['name'] ?>')" href="/admin/delete-category/<?= $row['id'] ?>" class="text-merah">Hapus</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="w-full mx-auto lg:w-9/12">
                <div class="h-[200px]">
                    <div x-show="open" class="flex flex-row text-center bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-center mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <h1 class="text-lg text-center text-white">Kategori masih kosong, silahkan tambah kategori</h1>
                        <!-- <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button> -->
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>

    </div>
</section>