<?php

namespace Drupal\livros\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

class LivrosForm extends FormBase {
  /**
   * Define um id unico para o form
   */
  public function getFormId() {
    return 'livros_form';
  }

  /**
   * Constroi um form
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['nome'] = [
      '#type' => 'textfield',
      '#title' => 'Nome do livro',
      '#required' => TRUE,
    ];
    $form['submit'] = [
      '#type' => 'submit',
      '#name' => 'Salvar',
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $connection = Database::getConnection();
    $connection->insert('livros')
      ->fields(['nome' => $form_state->getValue('nome')])
      ->execute();

    \Drupal::messenger()->addMessage('Livro cadastrado com sucesso!');
  }
}
