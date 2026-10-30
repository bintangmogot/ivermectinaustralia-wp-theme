<?php
/**
 * Module: support_cta.
 * Sub fields: eyebrow, title, desc, chat_btn_text, chat_btn_url, email_btn_text, email_btn_url, image.
 * A button shows only when it has a label and a link. The email link falls back to the Theme Settings support email.
 */
$GLOBALS['ivermectin_support_cta_rendered'] = true;

$eyebrow     = trim((string) get_sub_field('eyebrow'));
$title       = trim((string) get_sub_field('title'));
$description = trim((string) get_sub_field('desc'));
$chat_label  = trim((string) get_sub_field('chat_btn_text'));
$chat_url    = trim((string) get_sub_field('chat_btn_url'));
$email_label = trim((string) get_sub_field('email_btn_text'));
$email_url   = trim((string) get_sub_field('email_btn_url'));
$image_field = get_sub_field('image');

if ($chat_url !== '' && !preg_match('#^(https?://|/|\#|tel:|sms:)#i', $chat_url)) {
    $chat_url = ivermectin_format_whatsapp_url($chat_url);
}
if ($email_url === '') {
    $support_email = trim((string) get_field('support_email', 'option'));
    $email_url     = $support_email !== '' ? 'mailto:' . $support_email : '';
}
$image_url = is_array($image_field) ? ($image_field['url'] ?? '') : (is_numeric($image_field) ? (string) wp_get_attachment_image_url((int) $image_field, 'large') : '');

if ($title === '') {
    return;
}
?>
<section class="py-8">
    <div class="container-site">
        <div class="relative isolate overflow-hidden rounded-xl bg-ink text-white">
            <?php ivermectin_output_pattern_rings(); ?>
            <div class="relative z-10 grid items-center gap-6 p-6 md:p-10 <?= $image_url !== '' ? 'md:grid-cols-[1.4fr_1fr]' : '' ?>">
                <div>
                    <?php if ($eyebrow !== '') : ?><p class="iva-eyebrow text-white/70"><?= esc_html($eyebrow) ?></p><?php endif; ?>
                    <h2 class="mt-2 font-heading text-2xl font-extrabold text-white md:text-3xl"><?= esc_html($title) ?></h2>
                    <?php if ($description !== '') : ?><p class="mt-3 max-w-xl text-white/80"><?= esc_html($description) ?></p><?php endif; ?>
                    <div class="mt-5 flex flex-wrap gap-3">
                        <?php if ($chat_label !== '' && $chat_url !== '') : ?><a href="<?= esc_url($chat_url) ?>" class="btn-cta"><?= esc_html($chat_label) ?></a><?php endif; ?>
                        <?php if ($email_label !== '' && $email_url !== '') : ?><a href="<?= esc_url($email_url) ?>" class="btn-ghost"><?= esc_html($email_label) ?></a><?php endif; ?>
                    </div>
                </div>
                <?php if ($image_url !== '') : ?><img src="<?= esc_url($image_url) ?>" alt="" class="mx-auto h-auto max-h-72 w-auto object-contain" loading="lazy"><?php endif; ?>
            </div>
        </div>
    </div>
</section>
