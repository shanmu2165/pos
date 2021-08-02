<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>
<script src="client.js" defer></script>
<script src="https://js.stripe.com/terminal/v1/"></script>
<section class="content-part pb-4">
    <div class="booking-details pay-options min-normal bg-white">
        <div class="cart-title pt-4 pb-4">
            <div class="container">
                <div class="row">
                    <div class="col-12 text-center">
                        <h2>Step 4: Select Payment Options</h2>
                    </div>
                </div>
            </div>
            <div class="container pt-5 pb-5">
                
                    <form class="pt-5" action="<?= base_url().'/payment_success'; ?>" method="POST">
                    <div class="row justify-content-center">
                        <?= csrf_field() ?>
                        <div class="col-md-3 text-right">                           
                            <input type="submit" name="pay" class="btn btn-primary" value="Stripe" />
                        </div>
                        <div class="col-md-3 text-left">                           
                            <input type="submit" name="pay" class="btn btn-primary" value="Cash" />
                        </div>
                    </div>
                    </form>
                
            </div>
        </div>

    </div>
</section>
<?= $this->endSection(); ?>