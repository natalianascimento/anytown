<?php

namespace Drupal\livros\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;

class LivrosController extends ControllerBase {
  public function cadastraLivro() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\livros\Form\LivrosForm');

    return [
      '#title' => 'Cadastra Livro',
      '#markup' => $this->t('Ola, insira um livro aqui'),
      'form' => $form,
    ];
  }

  public function exibeLivro() {
    $connection = Database::getConnection();
    $query = $connection->select('livros', 'l')
      ->fields('l',['id', 'nome'])
      ->execute();

    $livros = $query->fetchAll();

    $rows = [];
    foreach ($livros as $livro) {
      $rows[] = [
        'data' => [$livro->id, $livro->nome],
      ];
    }

    $header = ['ID', 'Nome'];

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('Nenhum livro encontrado.'),
    ];
  }
}
