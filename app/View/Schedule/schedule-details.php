<section class="py-20 bg-nailart-bg">
    <div class="container mx-auto lg:py-28">
        <div class="w-9/12 mx-auto ">
            <div class="flex items-center justify-end w-9/12 gap-2 mx-auto mb-2">
            </div>
            <?php if (isset($model['schedule']) && $model['schedule'] != null && !isset($model['error'])) { ?>
                <div class="w-9/12 mx-auto rounded-[20px] overflow-hidden">
                    <table class="w-full font-rubik">
                        <thead class="border-b">
                            <tr class="bg-abu text-ungu">
                                <th class="py-4">
                                    Tanggal
                                </th>
                                <th class="py-4">
                                    Jam
                                </th>
                                <th class="py-4">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">

                            <?php
                            $i = 0;
                            foreach ($model['schedule'] as $row) {
                                $i++; ?>
                                <tr class="border-b">
                                    <td class="py-2 text-center bg-white"><?= $row['date'] ?></td>
                                    <td class="py-2 text-center bg-white"><?= $row['book_time'] ?></td>
                                    <form action="/admin/schedule/<?= $row['id'] ?>" method="post">
                                        <td class="py-2 text-center bg-white">
                                            <div>
                                                <select name="status" id="status" default="<?= $row['status'] ?>">
                                                    <option value="<?= $row['status'] ?>" selected disabled><?= $row['status'] ?></option>
                                                    <option value="Aktif">Aktif</option>
                                                    <option value="Non-aktif">Non-aktif</option>
                                                </select>
                                                <button type="submit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-merah">
                                                        <path d="M5.433 13.917l1.262-3.155A4 4 0 017.58 9.42l6.92-6.918a2.121 2.121 0 013 3l-6.92 6.918c-.383.383-.84.685-1.343.886l-3.154 1.262a.5.5 0 01-.65-.65z" />
                                                        <path d="M3.5 5.75c0-.69.56-1.25 1.25-1.25H10A.75.75 0 0010 3H4.75A2.75 2.75 0 002 5.75v9.5A2.75 2.75 0 004.75 18h9.5A2.75 2.75 0 0017 15.25V10a.75.75 0 00-1.5 0v5.25c0 .69-.56 1.25-1.25 1.25h-9.5c-.69 0-1.25-.56-1.25-1.25v-9.5z" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </form>
                                </tr>
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