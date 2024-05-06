<div class="group-select-container">
    <select id="group-select" class="group-select" data-controller="group" data-action="group#changeGroup">
        <?php foreach($options as $option) : ?>
            <option id="<?php esc_attr_e($option->id()) ?>" 
                value="<?php esc_attr_e($option->id()) ?>"
                <?php selected($option->id(), $group->id(), true); ?>
                data-testid="group-by-<?php esc_attr_e($option->id()) ?>"><?php esc_html_e($option->singular()); ?></option>
        <?php endforeach; ?>
    </select>
    <label><span class="dashicons dashicons-open-folder"></span></label>
</div>