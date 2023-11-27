<?php

$brothers_and_sisters = get_field('brothers_and_sisters');
?>
<div class="wcl-section">
    <?php if (!empty($brothers_and_sisters)) : ?>
        <div class="data-siblings">
            <div class="data-siblings-label mod-label">
                Рідні брати та сестри
            </div>

            <div class="data-siblings-text">
                <?php
                $items = explode(",", $brothers_and_sisters);

                foreach ($items as $item) {
                    echo $item . "<br>";
                }
                ?>
            </div>
        </div>
    <?php endif; ?>
</div>