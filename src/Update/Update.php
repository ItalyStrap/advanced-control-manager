<?php

/**
 * Update API Class
 *
 * This class manage the updating of the Settings and the Widget API
 *
 * @link [URL]
 * @since 2.3.0
 *
 * @package ItalyStrap\Update
 */

namespace ItalyStrap\Update;

use ItalyStrap\I18N\Translator;

/**
 * Class description
 */
class Update implements Update_Interface
{
    /**
     * Validation object
     */
    private ?\ItalyStrap\Update\Validation $validation = null;

    /**
     * Sanitization object
     */
    private \ItalyStrap\Update\Sanitization $sanitization;

    /**
     * [__construct description]
     *
     * @param [type] $argument [description].
     */
    function __construct(Validation $validation, Sanitization $sanitization)
    {
        $this->validation = $validation;
        $this->sanitization = $sanitization;
    }

    /**
     * Update method
     *
     * @param  array $instance The array with value to save.
     * @return array           The array validated and sanitized.
     */
    public function update(array $instance = [], array $fields = [])
    {

        foreach ($fields as $field) {
            if (! isset($instance[ $field['id'] ])) {
                $instance[ $field['id'] ] = '';
            }

            /**
             * Register string for translation
             */
            if (isset($field['translate']) && true === $field['translate']) {
                $this->translator->register_string($field['id'], strip_tags($instance[ $field['id'] ]));
            }

            /**
             * Validate fields if $field['validate'] is set
             */
            if (isset($field['validate'])) {
                if (false === $this->validation->validate($field['validate'], $instance[ $field['id'] ])) {
                    $instance[ $field['id'] ] = '';
                }
            }

            /**
             * @todo Fare il controllo che $instance[ $field['id'] ] non sia un array
             *       Nel caso fosse un array bisogna fare un sanitize apposito,
             *       per ora ho aggiunto un metodo ::sanitize_select_multiple() che
             *       sanitizza i valori nell'array ma bisogna sempre indicarlo
             *       nella configurazione del widget/option altrimenti da errore.
             *       Valutare anche in futuro di fare un metodo ricorsivo per array
             *       multidimensionali.
             *       Altre possibilità sono gli array con valori boleani o float e int
             *       Per ora sanitizza come fossero stringhe.
             */
            if (isset($field['capability']) && true === $field['capability']) {
                $instance[ $field['id'] ] = $instance[ $field['id'] ];
            } elseif (isset($field['sanitize'])) {
                $instance[ $field['id'] ] = $this->sanitization->sanitize($field['sanitize'], $instance[ $field['id'] ]);
            } else {
                $instance[ $field['id'] ] = strip_tags($instance[ $field['id'] ]);
            }
        }

        return $instance;
    }
}
