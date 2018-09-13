<?php

namespace Drupal\webform\EntitySettings;

use Drupal\Core\Form\FormStateInterface;

/**
 * Webform access settings.
 */
class WebformEntitySettingsAccessForm extends WebformEntitySettingsBaseForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    /** @var \Drupal\webform\WebformInterface $webform */
    $webform = $this->entity;

    $access = $webform->getAccessRules();
    $permissions = [
      'create' => $this->t('Create submissions'),
      'view_any' => $this->t('View any submissions'),
      'update_any' => $this->t('Update any submissions'),
      'delete_any' => $this->t('Delete any submissions'),
      'purge_any' => $this->t('Purge any submissions'),
      'view_own' => $this->t('View own submissions'),
      'update_own' => $this->t('Update own submissions'),
      'delete_own' => $this->t('Delete own submissions'),
      'administer' => $this->t('Administer webform &amp; submissions'),
      'test' => $this->t('Test webform'),
    ];

    $form['access']['#tree'] = TRUE;
    foreach ($permissions as $name => $title) {
      $form['access'][$name] = [
        '#type' => ($name === 'create') ? 'fieldset' : 'details',
        '#title' => $title,
        '#open' => ($access[$name]['roles'] || $access[$name]['users']) ? TRUE : FALSE,
      ];
      $form['access'][$name]['roles'] = [
        '#type' => 'webform_roles',
        '#title' => $this->t('Roles'),
        '#include_anonymous' => (!in_array($name, ['update_any', 'delete_any', 'purge_any'])) ? TRUE : FALSE,
        '#default_value' => $access[$name]['roles'],
      ];
      $form['access'][$name]['users'] = [
        '#type' => 'webform_users',
        '#title' => $this->t('Users'),
        '#default_value' => $access[$name]['users'] ? $this->entityTypeManager->getStorage('user')->loadMultiple($access[$name]['users']) : [],
      ];
      $form['access'][$name]['permissions'] = [
        '#type' => 'webform_permissions',
        '#title' => $this->t('Permissions'),
        '#multiple' => TRUE,
        '#select2' => TRUE,
        '#default_value' => $access[$name]['permissions'],
      ];
    }

    $form['access']['administer']['message'] = [
      '#weight' => -10,
      '#type' => 'webform_message',
      '#message_type' => 'warning',
      '#message_message' => $this->t('<strong>Warning</strong>: The below settings give users, permissions, and roles full access to this webform and its submissions.'),
    ];

    return parent::form($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $access = $form_state->getValue('access');

    /** @var \Drupal\webform\WebformInterface $webform */
    $webform = $this->getEntity();

    $webform->setAccessRules($access);

    parent::save($form, $form_state);
  }

}
