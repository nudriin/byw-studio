    <section class="py-20 bg-nailart-bg" x-data="{open : true}">
        <div class="container py-20 mx-auto">
            <?php if (isset($model['error'])) { ?>
                <div class="w-full mx-auto lg:w-9/12">
                    <div x-show="open" class="flex flex-row bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-between mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <h1 class="text-lg text-white"><?= $model['error'] ?></h1>
                        <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button>
                    </div>
                </div>
            <?php } ?>
            <div class="w-9/12 mx-auto">
                <div class="max-w-md mx-auto bg-white p-6 rounded-[20px] shadow-md">
                    <h2 class="mb-6 text-2xl font-medium font-rubik text-abu">Edit Kategori</h2>

                    <!-- Form -->
                    <form action="/admin/update-category/<?= $model['category']->id ?>" method="post" class="">
                        <!-- Nama Kategori -->
                        <div class="mb-4">
                            <label for="kategori" class="block mb-2 font-semibold text-abu">Nama
                                Kategori</label>
                            <input type="text" id="kategori" name="name" class="w-full px-4 py-2 border-2 rounded-md border-abu focus:outline-none focus:border-biru" placeholder="Masukkan nama kategori" value="<?= $model['category']->name ?>" required>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-abu text-ungu hover:shadow-md font-rubik">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>