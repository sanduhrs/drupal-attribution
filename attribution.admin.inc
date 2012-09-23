<?php

/**
 * Page callback to configure attribution.
 *
 * @ingroup forms
 */
function attribution_config_form() {
  $attribution = variable_get('attribution_text', array(
    'value' => '',
    'format' => NULL,
  ));

  $form = array();
  $form['attribution_text'] = array(
    '#type' => 'text_format',
    '#title' => t('Attribution text'),
    '#description' => t('This text will be added underneath the body field (or other chosen fields).<br />@max_length_message', array(
      '@max_length_message' => format_plural(512, 'Max length: @count character', 'Max length: @count characters'),
    )),
    '#default_value' => $attribution['value'],
    '#format' => $attribution['format'],
    '#max_length' => 512,
  );
  return system_settings_form($form);
}
