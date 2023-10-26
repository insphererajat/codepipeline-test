<?php if (isset($prompt)): ?>
    <option value=''><?= $prompt ?></option>
<?php endif; ?>
<?php if (isset($dropdownArr) && !empty($dropdownArr)): ?>
    <?php foreach ($dropdownArr as $code => $name): ?> 
        <option value='<?= $code?>'><?= $name ?></option>
    <?php endforeach; ?>
<?php endif; ?>
