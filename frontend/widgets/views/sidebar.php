<?php

use yii\helpers\Url;
?>
<div class="adm-c-sideBar__navigation withScroll">
    <ul class="adm-c-sideBar__navigation__grid">
        <li class="adm-c-sideBar__navigation__list section-header">Main Heading </li>
        <?php foreach ($menu as $item): ?>
            <?php if ($item['visibility']): ?>
                <li class="adm-c-sideBar__navigation__list <?=isset($item['childs']) && count($item['childs']) > 0 ? "dropdown js-sidebarList"  : "";?>">
                    <a href="<?= isset($item['url']) && !empty($item['url']) ? $item['url'] : "javascript:;"; ?>" class="adm-c-sideBar__navigation__item <?=isset($item['childs']) && count($item['childs']) > 0 ? "js-sidebarItem"  : "";?>">
                        <span class="adm-c-sideBar__navigation__item-icon">
                            <i class="<?= $item['icon']; ?>"></i>
                        </span>
                        <span class="adm-c-sideBar__navigation__item-text"><?= $item['name']; ?></span>
                    </a>
                    <?php  if (isset($item['childs']) && count($item['childs']) > 0): ?>
                       <ul class="sub-dropdown">
                            <?php foreach ($item['childs'] as $child): ?>
                                <li class="sub-dropdown__list">
                                    <a href="<?= isset($child['url']) && !empty($child['url']) ? $child['url'] : "javascript:;"; ?>" class="sub-dropdown__item"><?= $child['name']; ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                   <?php endif; ?>
                </li>
                <?php
            endif;
        endforeach;
        ?>
    </ul>
</div>