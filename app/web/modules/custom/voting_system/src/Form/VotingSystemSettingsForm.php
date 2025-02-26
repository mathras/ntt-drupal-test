<?php

namespace Drupal\voting_system\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class VotingSystemSettingsForm extends ConfigFormBase {

  public function getFormId() {
    return 'voting_system_settings_form';
  }

  protected function getEditableConfigNames() {
    return ['voting_system.settings'];
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('voting_system.settings');

    $form['disable_voting'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Desativar sistema de votação'),
      '#default_value' => $config->get('disable_voting'),
    ];

    return parent::buildForm($form, $form_state);
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('voting_system.settings')
      ->set('disable_voting', $form_state->getValue('disable_voting'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}