<?php

namespace Drupal\livros\Controller;

use Drupal\Core\Controller\ControllerBase;

class LivrosController extends ControllerBase {
  public function meuLivro() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\livros\Form\LivrosForm');

    return [
      '#title' => 'Cadastra Livro',
      '#markup' => $this->t('Ola, insira um livro aqui'),
      'form' => $form,
    ];
  }
}
