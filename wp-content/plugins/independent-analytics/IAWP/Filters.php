<?php

namespace IAWP_SCOPED\IAWP;

use IAWP_SCOPED\IAWP\Tables\Columns\Column;
use IAWP_SCOPED\IAWP\Utils\WordPress_Site_Date_Format_Pattern;
/** @internal */
class Filters
{
    public function get_filters_html(array $columns) : string
    {
        $opts = new Dashboard_Options();
        \ob_start();
        ?>
    <div class="modal-parent filters"
         data-controller="filters"
         data-filters-filters-value="<?php 
        \esc_html_e(\json_encode($opts->filters()));
        ?>"
    >
        <span class="dashicons dashicons-filter"></span>
        <div data-filters-target="conditionButtons" class="filter-condition-buttons">
            <?php 
        echo $this->condition_buttons_html($opts->filters());
        ?>
        </div>
        <button id="filters-button" class="filters-button"
                data-action="filters#toggleModal"
                data-filters-target="modalButton"
        >
            <span class="iawp-label"><?php 
        \esc_html_e('+ Add Filter', 'independent-analytics');
        ?></span>
        </button>
        <div id="modal-filters"
             class="modal large"
             data-filters-target="modal"
        >
            <div class="modal-inner">
                <div class="title-small"><?php 
        \esc_html_e('Filters', 'independent-analytics');
        ?></div>
                <div id="filters" data-filters-target="filters" class="filters" data-filters="[]">
                </div>
                <template data-filters-target="blueprint">
                    <?php 
        echo $this->get_condition_html($columns);
        ?>
                </template>
                <div>
                    <button id="add-condition" class="iawp-text-button"
                            data-action="filters#addCondition"
                    >
                        <?php 
        \esc_html_e('+ Add another condition', 'independent-analytics');
        ?>
                    </button>
                </div>
                <div class="actions">
                    <button id="filters-apply" class="iawp-button purple"
                            data-action="filters#apply"
                    >
                        <?php 
        \esc_html_e('Apply', 'independent-analytics');
        ?>
                    </button>
                    <button id="filters-reset" class="iawp-button ghost-purple"
                            data-action="filters#reset"
                            data-filters-target="reset"
                            disabled
                    >
                        <?php 
        \esc_html_e('Reset', 'independent-analytics');
        ?>
                    </button>
                </div>
            </div>
        </div>
        </div><?php 
        $html = \ob_get_contents();
        \ob_end_clean();
        return $html;
    }
    public function get_condition_html(array $columns)
    {
        \ob_start();
        ?>
        <div class="condition" data-filters-target="condition">
            <div class="input-group">
                <div>
                    <?php 
        echo self::get_inclusion_selects();
        ?>
                </div>
                <div>
                    <?php 
        self::get_column_select($columns);
        ?>
                </div>
                <div class="operator-select-container">
                    <?php 
        echo self::get_all_operator_selects();
        ?>
                </div>
                <div class="operand-field-container">
                    <?php 
        echo self::get_all_operand_fields($columns);
        ?>
                </div>
            </div>
            <button class="delete-button" data-action="filters#removeCondition">
                <span class="dashicons dashicons-no"></span></button>
        </div>
        <?php 
        $html = \ob_get_contents();
        \ob_end_clean();
        return $html;
    }
    public function condition_buttons_html(array $filters) : string
    {
        \ob_start();
        foreach ($filters as $filter) {
            ?>
            <button class="filters-condition-button"
                data-action="filters#toggleModal"
                data-filters-target="modalButton">
                <?php 
            echo \wp_kses_post($this->condition_string($filter));
            ?>
            </button>
        <?php 
        }
        $html = \ob_get_contents();
        \ob_end_clean();
        return $html;
    }
    public function condition_string(array $condition) : string
    {
        $full_string = '';
        foreach ($condition as $key => $value) {
            if (!\in_array($key, ['inclusion', 'column', 'operator', 'operand'])) {
                continue;
            }
            $condition_string = '';
            if ($key == 'inclusion' && $value == 'include') {
                $condition_string = \esc_html__('Include', 'independent-analytics');
            } elseif ($key == 'inclusion' && $value == 'exclude') {
                $condition_string = \esc_html__('Exclude', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'lesser') {
                $condition_string = '<';
            } elseif ($key == 'operator' && $value == 'greater') {
                $condition_string = '>';
            } elseif ($key == 'operator' && $value == 'equal') {
                $condition_string = '=';
            } elseif ($key == 'operator' && $value == 'on') {
                $condition_string = \esc_html__('On', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'before') {
                $condition_string = \esc_html__('Before', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'after') {
                $condition_string = \esc_html__('After', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'contains') {
                $condition_string = \esc_html__('Contains', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'exact') {
                $condition_string = \esc_html__('Exactly matches', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'is') {
                $condition_string = \esc_html__('Is', 'independent-analytics');
            } elseif ($key == 'operator' && $value == 'isnt') {
                $condition_string = \esc_html__("Isn't", 'independent-analytics');
            } elseif ($key == 'operand' && $condition['column'] == 'date') {
                try {
                    $date = \DateTime::createFromFormat('U', $value);
                    $condition_string = $date->format(WordPress_Site_Date_Format_Pattern::for_php());
                } catch (\Throwable $e) {
                    $condition_string = $value;
                }
            } elseif ($key == 'operand' && $condition['column'] == 'author') {
                $condition_string = \get_the_author_meta('display_name', $value);
            } elseif ($key == 'operand' && $condition['column'] == 'category') {
                $condition_string = \get_cat_name($value);
            } else {
                $condition_string .= \ucwords(\str_replace(['_', '-'], ' ', $value)) . ' ';
            }
            if ($key == 'column' || $key == 'operand') {
                $condition_string = '<strong>' . $condition_string . '</strong> ';
            }
            $full_string .= $condition_string;
        }
        return \trim($full_string);
    }
    private function get_inclusion_selects()
    {
        $html = '<select class="filters-include" data-filters-target="inclusion">';
        $html .= '<option value="include">' . \esc_html__('Include', 'independent-analytics') . '</option>';
        $html .= '<option value="exclude">' . \esc_html__('Exclude', 'independent-analytics') . '</option>';
        $html .= '</select>';
        return $html;
    }
    /**
     * @param Column[] $columns
     *
     * @return void
     */
    private function get_column_select(array $columns)
    {
        ?>
        <select class="filters-column" data-filters-target="column"
                data-action="filters#columnSelect"
        >
            <option value="">
                <?php 
        \esc_html_e('Choose a column', 'independent-analytics');
        ?>
            </option>
            <?php 
        foreach ($columns as $column) {
            ?>
                <?php 
            if ($column->requires_woocommerce() && !\IAWP_SCOPED\iawp_using_woocommerce()) {
                continue;
            }
            ?>
                <option value="<?php 
            echo \esc_attr($column->id());
            ?>"
                        data-type="<?php 
            echo \esc_attr($column->type());
            ?>"
                >
                    <?php 
            echo \esc_html($column->label());
            ?>
                </option>
            <?php 
        }
        ?>
        </select>
        <?php 
    }
    private function get_all_operator_selects()
    {
        $html = '';
        foreach (self::get_data_types() as $data_type) {
            $html .= '<select data-filters-target="operator" data-type="' . \esc_attr($data_type) . '" data-testid="' . \esc_attr($data_type) . '-operator">';
            foreach (self::get_operators($data_type) as $key => $value) {
                $html .= '<option value="' . \esc_attr($key) . '">' . \esc_html($value) . '</option>';
            }
            $html .= '</select>';
        }
        return $html;
    }
    private function get_all_operand_fields(array $columns)
    {
        $html = '';
        foreach ($columns as $column) {
            switch ($column->type()) {
                case 'string':
                    $html .= '<input data-filters-target="operand" data-action="keydown->filters#operandKeyDown filters#operandChange" data-column="' . \esc_attr($column->id()) . '" type="text" data-testid="' . \esc_attr($column->id()) . '-operand" placeholder="' . \esc_attr($column->filter_placeholder()) . '" />';
                    break;
                case 'int':
                    $html .= '<input data-filters-target="operand" data-action="keydown->filters#operandKeyDown filters#operandChange" data-column="' . \esc_attr($column->id()) . '" type="number" data-testid="' . \esc_attr($column->id()) . '-operand" placeholder="' . \esc_attr($column->filter_placeholder()) . '" />';
                    break;
                case 'date':
                    $html .= '<input type="text" 
                        data-filters-target="operand"
                        data-action="keydown->filters#operandKeyDown filters#operandChange"
                        data-column="' . \esc_attr($column->id()) . '"
                        data-controller="easepick"
                        data-css="' . \esc_url(\IAWP_SCOPED\iawp_url_to('dist/styles/easepick/datepicker.css')) . '" data-dow="' . \absint(\IAWP_SCOPED\iawp()->get_option('iawp_dow', 1)) . '" 
                        data-format="' . \esc_attr(WordPress_Site_Date_Format_Pattern::for_javascript()) . '" 
                        data-testid="' . \esc_attr($column->id()) . '-operand" />';
                    break;
                case 'select':
                    $html .= '<select data-filters-target="operand" data-column="' . \esc_attr($column->id()) . '" data-testid="' . \esc_attr($column->id()) . '-operand">';
                    foreach ($column->options() as $option) {
                        $html .= '<option value="' . \esc_attr($option[0]) . '">' . \esc_html($option[1]) . '</option>';
                    }
                    $html .= '</select>';
                    break;
            }
        }
        return $html;
    }
    private function get_data_types()
    {
        return ['string', 'int', 'date', 'select'];
    }
    private function get_operators(string $data_type)
    {
        if ($data_type == 'string') {
            return ['contains' => \esc_html__('Contains', 'independent-analytics'), 'exact' => \esc_html__('Exactly matches', 'independent-analytics')];
        } elseif ($data_type == 'int') {
            return ['greater' => \esc_html__('Greater than', 'independent-analytics'), 'lesser' => \esc_html__('Less than', 'independent-analytics'), 'equal' => \esc_html__('Equal to', 'independent-analytics')];
        } elseif ($data_type == 'select') {
            return ['is' => \esc_html__('Is', 'independent-analytics'), 'isnt' => \esc_html__('Isn\'t', 'independent-analytics')];
        } elseif ($data_type == 'date') {
            return ['before' => \esc_html__('Before', 'independent-analytics'), 'after' => \esc_html__('After', 'independent-analytics'), 'on' => \esc_html__('On', 'independent-analytics')];
        } else {
            return null;
        }
    }
}
