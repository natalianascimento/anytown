<?php

namespace Drupal\livros\Controller;

use Drupal\Core\Controller\ControllerBase;

class LivrosController extends ControllerBase {
  public function meuLivro() {
    return [
      '#markup' => $this->t('Ola')
    ];
  }
}
