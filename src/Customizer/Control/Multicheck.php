<?php

/**
 * Customizer for checkbox, extend the WP customizer
 *
 * @package ItalyStrap\Customizer
 * @since 2.5.0
 */

namespace ItalyStrap\Customizer\Control;

/**
 * Class for single checkbox in the customizer
 *
 * @link http://justintadlock.com/archives/2015/05/26/multiple-checkbox-customizer-control
 */
class Multicheck extends Control_Base
{
    /**
     * Render the control's content.
     *
     * @since 2.5.0
     */
    public function render_content()
    {

        if (empty($this->choices)) {
            return;
        }

        if (! empty($this->label)) : ?>
            <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        <?php endif;

        if (! empty($this->description)) : ?>
            <span class="description customize-control-description"><?php echo $this->description ; ?></span>
        <?php endif;

        $multi_values = ! is_array($this->value()) ? explode(',', $this->value()) : $this->value();
        ?>
        <ul>
        <?php
        foreach ($this->choices as $value => $label) :
            $name = sprintf(
                '_customize-checkbox-%s[%s]',
                $this->id,
                $value
            );
            ?>
            <li>
                <label>
                    <input type="checkbox" value="<?php echo esc_attr($value); ?>" <?php checked(in_array($value, $multi_values)); ?> /> 
                    <?php echo esc_html($label); ?>
                </label>
            </li>
            <?php
        endforeach;
        ?>
        </ul>
        <input type="hidden" <?php $this->link(); ?> value="<?php echo esc_attr(implode(',', $multi_values)); ?>" />
        <?php
    }
}
