<?php

namespace Drupal\livros\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\livros\Controller\LivrosController;

class AtualizarLivroForm extends FormBase {

  public function getFormId() {
    return 'livros_atualizar';
  }

  public function buildForm(array $form, FormStateInterface $form_state)   {
//    $controller = new LivrosController();
//    $livr
  }
}
