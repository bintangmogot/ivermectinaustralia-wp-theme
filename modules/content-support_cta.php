<?php
/**
 * Module: support_cta.
 * Sub fields: eyebrow, title, desc, chat_btn_text, chat_btn_url, email_btn_text, email_btn_url, image.
 * A solid navy block with the mascot peeking over the top edge; an image, when set, fills the right side.
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
$show_mascot = (bool) get_sub_field('show_mascot');

if ($chat_url !== '' && !preg_match('#^(https?://|/|\#|tel:|sms:)#i', $chat_url)) {
    $chat_url = ivermectin_format_whatsapp_url($chat_url);
}
if ($email_url === '') {
    $support_email = trim((string) get_field('support_email', 'option'));
    $email_url     = $support_email !== '' ? 'mailto:' . $support_email : '';
}
if (stripos($chat_url, 'tel:') === 0) {
    $chat_url = ivermectin_tel_url(substr($chat_url, 4)) ?: $chat_url;
}
$image_url = is_array($image_field) ? ($image_field['url'] ?? '') : (is_numeric($image_field) ? (string) wp_get_attachment_image_url((int) $image_field, 'large') : '');

if ($title === '') {
    return;
}
?>
<section class="iva-section iva-support-section bg-white">
    <div class="container-site">
        <div class="iva-support relative rounded-[1.75rem] bg-primary text-white sm:rounded-[2.5rem]" data-iva-support>
            <?php if ($show_mascot) { get_template_part('template-parts/mascot', null, ['pose' => 'peek', 'class' => 'iva-support-mascot']); } ?>
            <div class="grid <?= $image_url ? 'lg:grid-cols-12' : '' ?>">
                <div class="px-6 py-10 sm:p-12 md:p-16 <?= $image_url ? 'lg:col-span-7' : 'text-center' ?>">
                    <?php if ($eyebrow !== '') : ?><p class="iva-pill-tag iva-pill-tag-dark"><?= esc_html($eyebrow) ?></p><?php endif; ?>
                    <h2 class="iva-h2 mt-4 text-white <?= $image_url ? '' : 'mx-auto max-w-3xl' ?>"><?= esc_html($title) ?></h2>
                    <?php if ($description !== '') : ?><p class="iva-lead mt-4 max-w-xl text-white/75 <?= $image_url ? '' : 'mx-auto' ?>"><?= esc_html($description) ?></p><?php endif; ?>
                    <?php if (($chat_label !== '' && $chat_url !== '') || ($email_label !== '' && $email_url !== '')) : ?>
                        <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap <?= $image_url ? '' : 'sm:justify-center' ?>">
                            <?php if ($chat_label !== '' && $chat_url !== '') : ?>
                                <a href="<?= esc_url($chat_url, ['http', 'https', 'mailto', 'sms', 'tel']) ?>" class="iva-support-btn iva-support-btn-light">
                                    <?= ivermectin_icon('live_chat_support', 'h-6 w-6') ?>
                                    <span><?= esc_html($chat_label) ?></span>
                                </a>
                            <?php endif; ?>
                            <?php if ($email_label !== '' && $email_url !== '') : ?>
                                <a href="<?= esc_url($email_url, ['http', 'https', 'mailto']) ?>" class="iva-support-btn iva-support-btn-ghost">
                                    <svg class="h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="m3.5 6.5 8.5 6.5 8.5-6.5"/></svg>
                                    <span class="min-w-0 break-words"><?= esc_html($email_label) ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if ($image_url) : ?>
                    <div class="relative min-h-[280px] overflow-hidden rounded-b-[1.75rem] lg:col-span-5 lg:rounded-b-none lg:rounded-r-[2.5rem]">
                        <img src="<?= esc_url($image_url) ?>" alt="" class="absolute inset-0 h-full w-full object-cover" loading="lazy">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
