<section class="py-20 bg-nailart-bg">
    <div class="container mx-auto lg:py-28">
        <div class="w-9/12 mx-auto ">
            <div class="flex items-center justify-end w-9/12 gap-2 mx-auto mb-2">
            </div>
            <?php if (isset($model['orders']) && isset($model['schedule']) && $model['orders'] != null && $model['schedule'] != null && !isset($model['error'])) { ?>
                <div class="w-64 mx-auto ">
                    <div class="h-full">
                        <div class="bg-abu rounded-[15px] text-ungu" style="padding: 48px;">
                            <form id="bookingForm" action="/customer/booking/<?= $model['schedule'][0]['date'] ?>/<?= $model['orders_id'] ?>" method="post" class="flex flex-col font-rubik">
                                <input type="hidden" name="id" value="<?= $model['orders_id'] ?>">
                                <input type="hidden" name="schedule_id" value="<?= $model['orders_id'] ?>">
                                <label for="">Jam Booking</label>
                                <?php if ($model['orders_by_id']->book_time == null) { ?>
                                    <select onchange="this.form.submit()" name="book_time" id="selectedDate" class="p-2 border-2 border-white rounded-lg bg-abu">
                                    <?php } else { ?>
                                        <select disabled onchange="this.form.submit()" name="book_time" id="selectedDate" class="p-2 border-2 border-white rounded-lg bg-abu">
                                        <?php } ?>
                                        <option value="Pilih Jam" selected disabled>Pilih Jam</option>
                                        <?php foreach ($model['schedule'] as $row) { ?>
                                            <?php if ($row['status'] == "Aktif") { ?>
                                                <!-- <input type="hidden" name="selected_id" value="<?= $row['id'] ?>"> -->
                                                <option value="<?= $row['book_time'] ?>"><?= $row['book_time'] ?></option>
                                            <?php } ?>
                                        <?php } ?>
                                        </select>
                            </form>
                        </div>
                    </div>
                </div>
            <?php } else { ?>

            <?php } ?>
        </div>
        <script>
            function disableDateInput() {
                // Disable the date input after the first selection
                document.getElementById('selectedDate').disabled = true;

                document.getElementById('bookingForm').submit();
            }
        </script>
    </div>
</section>