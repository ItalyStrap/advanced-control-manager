<?php

/**
 * Class API for custom query
 *
 * @since 2.0.0
 *
 * @version 1.0.0
 *
 * @package ItalyStrap
 */

namespace ItalyStrap\VCard;

/**
 * The vCard Class
 */
class VCard
{
    /**
     * [$var description]
     *
     * @var null
     */
    private $args = null;

    /**
     * Get arguments
     *
     * @param  string $context The context.
     * @param  array  $args    The arguments for this class.
     * @param  array  $default The default arguments.
     */
    public function get_args($context, array $args, array $default)
    {

        /**
         * Define data by given attributes.
         */
        $args = \ItalyStrap\Core\shortcode_atts_multidimensional_array($default, $args, $context);

        $args = apply_filters('italystrap_{$context}_args', $args);

        $this->args = $args;
    }

    /**
     * Output the query result
     *
     * @return string The HTML result
     */
    public function output()
    {

        ob_start();

        require(\ItalyStrap\Core\get_template('templates/content-vcard.php'));

        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    /**
     * Get the company name
     *
     * @param  bool $echo Echo it if is true.
     *
     * @return string       If echo is false return
     */
    public function get_company_name($echo = false)
    {

        $company_name = '';

        if ($this->args['company_name']) {
            $company_name = esc_textarea($this->args['company_name']);
        } else {
            $company_name = get_bloginfo('name');
        }

        if ($echo) {
            echo $company_name; // XSS ok.
        } else {
            return $company_name;
        }
    }

    /**
     * Get itemprop sameAs
     *
     * @param  bool $echo Echo it if is true.
     *
     * @return string       If echo is false return
     */
    public function get_itemprop_sameas($echo = false)
    {

        $same_as = ['facebook', 'twitter', 'googleplus', 'pinterest', 'instagram', 'youtube', 'linkedin'];

        $meta = '';

        foreach ($same_as as $social) {
            if ($this->args[ $social ]) {
                $meta .= '<meta itemprop="sameAs" content="' . esc_url($this->args[ $social ]) . '"/>';
            }
        }

        if ($echo) {
            echo $meta; // XSS ok.
        } else {
            return $meta;
        }
    }

    /**
     * Get itemprop contacts
     *
     * @param  bool $echo Echo it if is true.
     *
     * @return string If echo is false return
     */
    public function get_itemprop_contacts($echo = false)
    {

        $contacts = ['tel'       => 'telephone', 'mobile'    => 'telephone', 'fax'       => 'faxNumber', 'email'     => 'email', 'taxID'     => 'taxID'];

        $contacts_list = '';

        foreach ($contacts as $contact_key => $itemprop) {
            if ($this->args[ $contact_key ]) {
                if ('email' === $contact_key) {
                    $contacts_list .= '<li class="' . $itemprop . '" itemprop="' . $itemprop . '"><a href="mailto:' . antispambot(is_email($this->args[ $contact_key ])) . '">' . antispambot(is_email($this->args[ $contact_key ])) . '</a></li>'; // XSS ok.
                } elseif ('taxID' === $contact_key) {
                    $contacts_list .= '<li class="' . $itemprop . '" itemprop="' . $itemprop . '"><span>P.IVA</span> ' . esc_textarea($this->args[ $contact_key ]) . '</li>';
                } else {
                    $contacts_list .= '<li class="' . $itemprop . '" itemprop="' . $itemprop . '">' . esc_textarea($this->args[ $contact_key ]) . '</li>';
                }
            }
        }

        if ($echo) {
            echo $contacts_list; // XSS ok.
        } else {
            return $contacts_list;
        }
    }
}
