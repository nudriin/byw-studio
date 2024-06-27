<section class="py-20 bg-nailart-bg" x-data="{open : true}">
    <div class="container mx-auto lg:py-28">
        <?php if (isset($model['category']) && $model['category'] != null && isset($model['nailist']) && $model['nailist'] != null && isset($model['date']) && $model['date'] != null && isset($model['customer']) && $model['customer'] != null && !isset($model['error'])) { ?>
            <div class="w-9/12 mx-auto">
                <!-- <div class="bg-dark-white rounded-[20px] shadow-lg"> -->
                <form action="/booking/orders" method="post" enctype="multipart/form-data" class="flex flex-col items-center justify-center gap-4">
                    <!-- <div class="w-9/12">
                            <div class="flex flex-col px-4 py-2 bg-white rounded-lg">
                                <span class="text-sm font-rubik">Selasa, 2 Oktober 2023</span>
                                <span class="text-2xl font-rubik">15:00</span>
                            </div>
                        </div> -->
                    <div class="flex flex-col w-9/12">
                        <label for="name" class="font-rubik text-ungu">Nama Lengkap</label>
                        <input type="text" name="name" id="" class="px-4 py-2 bg-white border-2 rounded-lg" value="<?= $model['customer']->name ?>" disabled>
                    </div>
                    <!-- <div class="flex flex-col w-9/12 ">
                    <label for="name" class="font-rubik text-ungu">Email</label>
                    <input type="email" name="email" id="" class="px-4 py-2 bg-white border-2 rounded-lg" placeholder="Email" required>
                </div> -->
                    <div class="flex flex-col w-9/12 ">
                        <label for="name" class="font-rubik text-ungu">Nomor Telepon</label>
                        <input type="number" min="0" name="phone" id="" class="px-4 py-2 bg-white border-2 rounded-lg" value="<?= $model['customer']->phone ?>" disabled>
                    </div>
                    <div class="flex flex-col w-9/12 ">
                        <label for="name" class="font-rubik text-ungu">Kategori</label>
                        <select name="category" id="" class="px-4 py-2 bg-white border-2 rounded-lg" required>
                            <option value="" disabled selected>Pilih Kategori</option>
                            <?php foreach ($model['category'] as $row) { ?>
                                <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="flex flex-col w-9/12">
                        <label for="name" class="font-rubik text-ungu">Nailist</label>
                        <select name="nailist" id="" class="px-4 py-2 bg-white border-2 rounded-lg" required>
                            <option value="" disabled selected>Pilih Nailist</option>
                            <?php foreach ($model['nailist'] as $row) { ?>
                                <option value="<?= $row['name'] ?>"><?= $row['name'] ?></option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="flex flex-col w-9/12">
                        <label for="name" class="font-rubik text-ungu">Tanggal Booking</label>
                        <select name="date" id="" class="px-4 py-2 bg-white border-2 rounded-lg" required>
                            <option value="" disabled selected>Pilih Tanggal</option>
                            <?php foreach ($model['date'] as $row) { ?>
                                <option value="<?= $row['date'] ?>"><?= $row['date'] ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <!-- <div class="flex flex-col w-9/12">
                            <label for="name" class="font-rubik text-ungu">Jam Booking</label>
                            <select name="book_time" id="" class="px-4 py-2 bg-white border-2 rounded-lg" required>
                                <option value="" disabled selected>Pilih Jam</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div> -->
                    <div class="w-9/12">
                        <div class="flex flex-col gap-2 px-4 py-2 bg-white rounded-lg">
                            <div>
                                <span class="font-rubik">DP</span>
                                <span class="font-rubik text-merah">Rp 50.000</span>
                            </div>
                            <div>
                                <span>No Rekening</span>
                                <span class="font-rubik text-merah">4552 - 0102 - 4954 - 539</span>
                            </div>
                            <div>
                                <span>BRI an Gress Sheilla</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-col w-9/12">
                        <label for="name" class="font-rubik text-ungu">Bukti Pembayaran</label>
                        <input type="file" name="payment_confirm" id="" class="block w-full px-4 py-2 mb-4 text-sm bg-white border-2 rounded-lg text-abu file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-rubik file:bg-abu" required>
                    </div>
                    <div class="w-9/12 px-4 py-2 mx-auto bg-white rounded-lg">
                        <div class="flex items-center justify-center">
                            <span class="text-2xl text-center font-rubik">Perhatian</span>
                        </div>
                        <p>Harap periksa kembali data booking Anda</p>
                        <ul>
                            <li>- Pastikan data yang anda masukan telah sesuai</li>
                            <li>- Silahkan lakukan pembayaran DP sebesar Rp. 50.000,- agar booking dapat di proses</li>
                            <li>- Setelah melakukan booking, harap mengkonfirmasi jam booking pada laman booking di bagian dashboard</li>
                        </ul>
                        <div class="flex items-center mt-2">
                            <span class="text-xl font-rubik">Alamat : Jl. Sisingamangaraja, Menteng, Kec. Jekan Raya, Kota Palangka Raya, Kalimantan Tengah 74874</span>
                        </div>
                    </div>
                    <div class="w-[450px] flex flex-col text-dark-white">
                        <button type="submit" class="rounded-[15px] text-lg font-rubik bg-abu text-ungu py-2 px-4 shadow-md shadow-ungu">Booking</button>
                    </div>
                </form>
                <!-- </div> -->
            </div>
        <?php } else { ?>
            <div class="w-full mx-auto lg:w-9/12">
                <div class="h-[200px]">
                    <div x-show="open" class="flex flex-row text-center bg-red-600 rounded-[20px] opacity-50 py-3 px-5 items-center justify-center mb-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
                        <h1 class="text-lg text-center text-white"><?= $model['error'] ?></h1>
                        <!-- <button @click="open = !open" class="px-4 py-2 text-white border-2 rounded-full border-inherit">X</button> -->
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</section>