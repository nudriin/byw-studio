<section class="py-20 bg-nailart-bg">
    <div class="container mx-auto lg:py-28">
        <div class="w-9/12 mx-auto ">
            <div class="flex items-center justify-end w-9/12 gap-2 mx-auto mb-2">
            </div>
            <?php if (isset($model['orders']) && $model['orders'] != null && !isset($model['error'])) { ?>
                <div class="w-9/12 mx-auto rounded-[20px] overflow-hidden">
                    <table class="w-full font-rubik">
                        <thead class="border-b">
                            <tr class="bg-abu text-ungu">
                                <th class="py-4">
                                    Paket
                                </th>
                                <th class="py-4">
                                    Nailist
                                </th>
                                <th class="py-4">
                                    Tanggal
                                </th>
                                <th class="py-4">
                                    Jam
                                </th>
                                <th class="py-4">
                                    Status
                                </th>
                                <th class="py-4">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">

                            <?php
                            $i = 0;
                            foreach ($model['orders'] as $row) {
                                $i++; ?>
                                <?php if ($row['status'] != "Selesai") { ?>
                                    <tr class="border-b hover:bg-dark-white">
                                        <td class="py-2 text-center bg-white"><?= $row['category_name'] ?></td>
                                        <td class="py-2 text-center bg-white"><?= $row['nailist_name'] ?></td>
                                        <td class="py-2 text-center bg-white"><?= $row['date'] ?></td>
                                        <td class="py-2 text-center bg-white"><?= $row['book_time'] != null ? $row['book_time'] : "Silahkan konfimasi" ?></td>
                                        <td class="py-2 text-center bg-white"><?= $row['status'] ?></td>
                                        <?php if($row['book_time'] != null) { ?>
                                            <td class="py-2 text-center bg-white">
                                                <button disabled class="p-2 bg-abu rounded-[15px] text-ungu" style="opacity: 0.75; width: 130px;">-</button>
                                            </td>
                                        <?php } else { ?>
                                        <td class="py-2 text-center bg-white">
                                            <a href="/customer/booking/<?= $row['date'] ?>/<?= $row['id'] ?>" class="p-2 bg-abu rounded-[15px] text-ungu">Konfimasi jam</a>
                                        </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } else { ?>
                <div class="w-full mx-auto lg:w-9/12">
                    <div x-show="open" class="flex flex-row bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-between mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <h1 class="text-lg text-white"><?= $model['error'] ?></h1>
                        <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button>
                    </div>
                </div>
            <?php } ?>
        </div>

    </div>
</section>