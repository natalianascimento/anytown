<?php

declare(strict_types=1);

namespace Drupal\livros\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\StringTranslation\TranslatableMarkup;

#[Block(
  id: 'novo-livro',
  admin_label: new TranslatableMarkup('Novo Livro'),
  category: new TranslatableMarkup('Custom')
)]
class NovoLivroBlock extends BlockBase
{
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('Livro nov aqui!'),
    ];
    return $build;
  }
}
