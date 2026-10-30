<?php
/**
 * Thank you page.
 * Bank details come from the WooCommerce BACS settings, the PayID from Theme Settings.
 * Wording comes from the Checkout & Thank You tab in Theme Settings.
 *
 * @var WC_Order $order
 */

defined('ABSPATH') || exit;

$opt = static function ($name) {
    return function_exists('get_field') ? trim((string) get_field($name, 'option')) : '';
};

$heading       = $opt('thankyou_heading');
$intro         = $opt('thankyou_text');
$bacs_warning  = $opt('checkout_bacs_warning');
$receipt       = $opt('checkout_receipt_notice');
$shipping_note = $opt('checkout_shipping_note');
$support_email = $opt('support_email');
$payid         = function_exists('ivermectin_get_payid') ? ivermectin_get_payid() : '';

if ($receipt !== '') {
    $receipt = str_replace('{email}', $support_email, $receipt);
}

$accounts = [];
if ($order && $order->get_payment_method() === 'bacs' && function_exists('WC') && WC()->payment_gateways()) {
    $gateways = WC()->payment_gateways()->payment_gateways();
    if (isset($gateways['bacs']) && is_array($gateways['bacs']->account_details)) {
        $accounts = $gateways['bacs']->account_details;
    }
}
?>
<div class="woocommerce-order mx-auto max-w-3xl px-3 py-6 sm:px-6 sm:py-8">
<?php if ($order) : ?>
    <?php do_action('woocommerce_before_thankyou', $order->get_id()); ?>

    <?php if ($order->has_status('failed')) : ?>
        <div class="rounded-2xl border border-destructive/30 bg-white p-6 text-center">
            <p class="font-heading text-xl font-extrabold text-foreground"><?= esc_html__('Payment failed', 'ivermectin-australia') ?></p>
            <p class="mt-2 text-muted-foreground"><?= esc_html__('Your payment could not be completed. Please try again.', 'ivermectin-australia') ?></p>
            <div class="mt-4 flex flex-wrap justify-center gap-3">
                <a href="<?= esc_url($order->get_checkout_payment_url()) ?>" class="btn-cta"><?= esc_html__('Pay', 'ivermectin-australia') ?></a>
                <?php if (is_user_logged_in()) : ?><a href="<?= esc_url(wc_get_page_permalink('myaccount')) ?>" class="btn-navy"><?= esc_html__('My account', 'ivermectin-australia') ?></a><?php endif; ?>
            </div>
        </div>
    <?php else : ?>
        <div class="text-center">
            <?= ivermectin_icon('verified_reviews', 'mx-auto h-16 w-16') ?>
            <?php if ($heading !== '') : ?><h1 class="mt-3 font-heading text-3xl font-extrabold text-primary"><?= esc_html($heading) ?></h1><?php endif; ?>
            <?php if ($intro !== '') : ?><p class="mx-auto mt-2 max-w-xl text-muted-foreground"><?= esc_html($intro) ?></p><?php endif; ?>
        </div>

        <div class="mt-6 grid grid-cols-2 gap-4 rounded-2xl border border-border bg-white p-4 md:grid-cols-4">
            <div><p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground"><?= esc_html__('Order Number', 'ivermectin-australia') ?></p><p class="font-mono text-lg font-bold">#<?= esc_html($order->get_order_number()) ?></p></div>
            <div><p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground"><?= esc_html__('Date', 'ivermectin-australia') ?></p><p class="font-bold"><?= esc_html(wc_format_datetime($order->get_date_created())) ?></p></div>
            <div><p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground"><?= esc_html__('Total', 'ivermectin-australia') ?></p><p class="font-heading text-xl font-extrabold text-primary"><?= wp_kses_post($order->get_formatted_order_total()) ?></p></div>
            <div><p class="text-xs font-semibold uppercase tracking-wider text-muted-foreground"><?= esc_html__('Payment Method', 'ivermectin-australia') ?></p><p class="font-bold"><?= wp_kses_post($order->get_payment_method_title()) ?></p></div>
        </div>

        <?php if ($accounts || $payid !== '') : ?>
            <div class="mt-5 rounded-2xl border border-border bg-primary-softer p-6">
                <h2 class="mb-4 flex items-center gap-3 font-heading text-xl font-extrabold text-primary"><?= ivermectin_icon('bank_transfer', 'h-9 w-9') ?><?= esc_html__('Payment Details', 'ivermectin-australia') ?></h2>
                <dl class="divide-y divide-border">
                    <?php if ($payid !== '') : ?>
                        <div class="flex items-center justify-between gap-3 py-2.5"><dt class="text-sm text-muted-foreground"><?= esc_html__('PayID', 'ivermectin-australia') ?></dt><dd class="flex items-center gap-2 font-bold"><span data-iva-copy-value><?= esc_html($payid) ?></span><button type="button" class="rounded-md border border-border bg-white px-2 py-1 text-xs font-bold text-primary" data-iva-copy data-iva-copied="<?= esc_attr__('Copied', 'ivermectin-australia') ?>"><?= esc_html__('Copy', 'ivermectin-australia') ?></button></dd></div>
                    <?php endif; ?>
                    <?php foreach ($accounts as $account) :
                        $rows = [
                            __('Bank', 'ivermectin-australia')           => $account['bank_name'] ?? '',
                            __('Account Name', 'ivermectin-australia')   => $account['account_name'] ?? '',
                            __('BSB', 'ivermectin-australia')            => $account['sort_code'] ?? '',
                            __('Account Number', 'ivermectin-australia') => $account['account_number'] ?? '',
                            __('SWIFT/BIC', 'ivermectin-australia')      => $account['bic'] ?? '',
                        ];
                        foreach ($rows as $label => $value) : if (trim((string) $value) === '') { continue; } ?>
                            <div class="flex items-center justify-between gap-3 py-2.5"><dt class="text-sm text-muted-foreground"><?= esc_html($label) ?></dt><dd class="flex items-center gap-2 font-bold"><span data-iva-copy-value><?= esc_html($value) ?></span><button type="button" class="rounded-md border border-border bg-white px-2 py-1 text-xs font-bold text-primary" data-iva-copy data-iva-copied="<?= esc_attr__('Copied', 'ivermectin-australia') ?>"><?= esc_html__('Copy', 'ivermectin-australia') ?></button></dd></div>
                        <?php endforeach;
                    endforeach; ?>
                    <div class="flex items-center justify-between gap-3 py-2.5"><dt class="text-sm text-muted-foreground"><?= esc_html__('Reference', 'ivermectin-australia') ?></dt><dd class="font-bold">#<?= esc_html($order->get_order_number()) ?></dd></div>
                </dl>
                <?php if ($bacs_warning !== '') : ?><p class="mt-4 rounded-lg bg-white p-3 text-sm font-semibold text-destructive"><?= esc_html($bacs_warning) ?></p><?php endif; ?>
                <?php if ($receipt !== '') : ?><p class="mt-3 text-sm text-muted-foreground"><?= esc_html($receipt) ?></p><?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($shipping_note !== '') : ?>
            <div class="mt-5 flex items-start gap-3 rounded-2xl border border-border bg-white p-5">
                <?= ivermectin_icon('calendar_days', 'h-9 w-9') ?>
                <p class="text-sm leading-relaxed text-muted-foreground"><?= esc_html($shipping_note) ?></p>
            </div>
        <?php endif; ?>

        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

        <p class="mt-6 text-center"><a href="<?= esc_url(wc_get_page_permalink('shop')) ?>" class="btn-navy"><?= esc_html__('Continue Shopping', 'ivermectin-australia') ?></a></p>
    <?php endif; ?>
<?php else : ?>
    <?php wc_get_template('checkout/order-received.php', ['order' => false]); ?>
<?php endif; ?>
</div>
