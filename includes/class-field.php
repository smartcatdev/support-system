<?php

namespace ucare;


class Field {

    public $id;
    public $label;
    public $description;

    public $value;

    public $config;
    public $attributes;


    public function __construct( array $args ) {

        $defaults = array(
            'id'                => null,
            'label'             => '',
            'value'             => '',
            'config'            => array(),
            'attributes'        => array(),
            'description'       => ''
        );

        $args = wp_parse_args( $args, $defaults );

        $this->id    = $args['id'];
        $this->label = $args['label'];
        $this->value = $args['value'];

        $this->description = $args['description'];

        $this->config     = $args['config'];
        $this->attributes = $args['attributes'];

    }

}
