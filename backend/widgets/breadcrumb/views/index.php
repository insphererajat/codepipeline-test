<div class="adm-c-pageHeader design1 cmt-15">
  <?php if (!empty($this->params['breadcrumb'])) : ?>
    <div class="adm-c-pageHeader__item">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb adm-c-pageHeader__breadcrumb">
          <?php foreach ($this->params['breadcrumb'] as $breadcrumb) : ?>
            <li class="breadcrumb-item <?= isset($breadcrumb['class']) ? $breadcrumb['class'] : "" ?>"><a href="<?= !empty($breadcrumb['url']) ? $breadcrumb['url'] : "javascript:;"; ?>"><?= $breadcrumb['label']; ?></a></li>
          <?php endforeach; ?>
        </ol>
      </nav>
    </div>
  <?php endif; ?>
  <?php if (isset($this->params['breadcrumbMenu'])) : ?>
    <div class="adm-c-pageHeader__item ml-auto">
      <ul class="adm-c-pageHeader__action">
        <?php foreach ($this->params['breadcrumbMenu'] as $menu) : ?>
          <?php if (isset($menu['customHtml'])) : ?>
           <?=  $menu['customHtml'] ?>
          <?php else : ?>
            <li class="adm-c-pageHeader__action-item">
              <a href="<?= $menu['url'] ?>" class="adm-c-pageHeader__action-item-link <?= !empty($menu['class']) ? $menu['class'] : ""; ?>">
                <?php if (isset($menu['icon'])) : ?>
                  <span class="adm-c-pageHeader__action-item-icon <?= $menu['icon'] ?>"></span>
                <?php endif; ?>
                <span class="adm-c-pageHeader__action-item-value"><?= $menu['label'] ?></span>
              </a>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
</div>