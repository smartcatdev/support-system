<?php

/**
 * This file stitches together the application.
 */

use SmartcatSupport\admin\SupportMetaBox;
use SmartcatSupport\ajax\Comment;
use SmartcatSupport\ajax\TicketTable;
use SmartcatSupport\ajax\Ticket;
use SmartcatSupport\form\FormBuilder;
use SmartcatSupport\util\Installer;
use SmartcatSupport\util\TemplateRender;

// Setup plugin contexts
$app['plugin_dir'] = plugin_dir_path( $fs_context );
$app['plugin_url'] = plugin_dir_url( $fs_context );

// Default template rendering
$app['renderer'] = new TemplateRender( $app['plugin_dir'] . '/templates' );

// Form Builder Factory
$app['form_factory'] = new FormBuilder( 'support_form' );

// Configure table Handler
$app['table_handler'] = new TicketTable( $app['renderer'], $app['form_factory'] );

// Configure ticket Handler
$app['ticket_handler'] = new Ticket( $app['renderer'], $app['form_factory'] );

// Configure comment handler
$app['comment_handler'] = new Comment( $app['renderer'], $app['form_factory'] );

// Configure the metabox
$app['support_metabox'] = new SupportMetaBox( $app['renderer'], $app['form_factory'] );

// Configure installer
$app['installer'] = new Installer();
