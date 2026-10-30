<?php
/**
 * Module: Text Full Width (For Privacy Policy, Terms, etc.)
 */
?>
<section class="section-padding bg-background">
    <div class="container-custom max-w-4xl">
        <div class="prose prose-slate max-w-none prose-a:text-primary hover:prose-a:text-primary-dark prose-headings:font-heading prose-headings:font-bold">
            <?php 
            $content = get_sub_field('content');

            if ($content) {
                echo $content;
            } else {
                echo get_sub_field('content'); // Fallback if no translation fields
            }
            ?>
        </div>
    </div>
</section>
