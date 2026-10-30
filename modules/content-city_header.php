<?php
/**
 * Module: City Header (SEO Pages)
 */

$city_name = get_sub_field('city_name') ?: "Kuala Lumpur";
$state_name = get_sub_field('state_name') ?: "W.P. Kuala Lumpur";

$heading = get_sub_field('heading') ?: "Buy Modafinil in {$city_name}, {$state_name}";

$desc = get_sub_field('description') ?: "Fast, discreet delivery of genuine Modafinil to all areas in {$city_name}. Tracked shipping via Australia Post.";
?>
<section class="bg-background py-8 md:py-10 text-center border-b border-border">
    <div class="container-site max-w-4xl">
        <span class="inline-block rounded-full bg-primary-softer px-4 py-1.5 text-xs font-bold uppercase tracking-wider text-primary">
            <?= "Local Delivery" ?>
        </span>
        <h1 class="mt-4 font-heading text-4xl font-extrabold tracking-tight md:text-5xl">
            <?= $heading ?>
        </h1>
        <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-muted-foreground">
            <?= $desc ?>
        </p>
    </div>
</section>
