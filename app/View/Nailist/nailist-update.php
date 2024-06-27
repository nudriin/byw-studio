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
            <div class="grid items-center justify-center grid-cols-12 gap-4">
                <div class="col-span-6">
                    <div class="flex items-center justify-center">
                        <h1 class="mb-4 text-4xl font-rubik">Edit Nailist</h1>
                    </div>
                    <form action="/admin/update-nailist/<?= $model['nailist']->id ?>" method="post" class="col-span-6" enctype="multipart/form-data">
                        <div class="items-center justify-center gap-3">
                            <div>
                                <input type="text" name="name" id="" placeholder="Name" class="w-full p-2 mb-4 border-2 rounded-lg border-abu" value="<?= $model['nailist']->name ?>" required>
                            </div>
                            <div>
                                <input type="file" name="picture" id="picture" value="" placeholder="Picture" class="block w-full mb-4 text-sm cursor-pointer text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-rubik file:bg-ungu" required>
                            </div>
                            <script>
                                document.getElementById("picture").defaultValue = "<?= getUploadedImg("nailist/{$model['nailist']->picture}") ?>"
                            </script>
                            <div>
                                <button type="submit" class="w-full p-2 mb-4 rounded-lg font-rubik bg-ungu text-abu">Simpan</button>
                            </div>
                            <a onclick="return confirm('Anda yakin ingin menghapus <?= $model['nailist']->name ?>')" href="/admin/delete-nailist/<?= $model['nailist']->id ?>">
                                <div class="w-full">
                                    <div class="p-2 text-center rounded-lg bg-merah text-dark-white font-rubik">Hapus</div>
                                </div>
                            </a>
                        </div>
                    </form>
                </div>
                <div class="w-full col-span-6">
                    <div class="h-full overflow-hidden">
                        <img src="<?= getUploadedImg("nailist/{$model['nailist']->picture}") ?>" id="profile_img" class="rounded-[20px] object-cover object-center w-full h-[420px]" alt="">
                    </div>
                </div>
            </div>
            <!-- <div class="bg-dark-white rounded-[20px] shadow-lg"> -->
            <!-- </div> -->
        </div>
    </div>
    <script type="text/javascript">
        document.getElementById("picture").onchange = function() {
            document.getElementById("profile_img").src = URL.createObjectURL(picture.files[0]);
            document.getElementById("cancel").style.display = "block";

            document.getElementById("upload").style.display = "none";
        }

        var userImg = document.getElementById('profile_img').src;
        document.getElementById("cancel").onclick = function() {
            document.getElementById('profile_img').src = userImg;
            document.getElementById("cancel").style.display = "none";

            document.getElementById("upload").style.display = "block";
        }
    </script>
</section>