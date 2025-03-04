<?php

declare(strict_types=1);

namespace Drupal\anytown\Controller;

use Drupal\anytown\ForecastClientInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for anytown.weather_page route.
 */
class WeatherPage extends ControllerBase {

  /**
   * Forecast API client.
   *
   * @var \Drupal\anytown\ForecastClientInterface
   */
  private $forecastClient;

  /**
   * WeatherPage controller constructor.
   *
   * @param \Drupal\anytown\ForecastClientInterface $forecast_client
   * Forecast API client service.
   */
  public function __construct(ForecastClientInterface $forecast_client) {
    $this->forecastClient = $forecast_client;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('anytown.forecast_client')
    );
  }

  /**
   * Builds the response
   */
  public function build(string $style): array {
    // Style should be one of 'short', or 'extended'. And default to 'short'.
    $style = (in_array($style, ['short', 'extended'])) ? $style : 'short';

    $url = 'https://module-developer-guide-demo-site.ddev.site:4453/modules/custom/anytown/data/weather_forecast.json';
    $forecast_data = $this->forecastClient->getForecastData($url);

    $table_rows = [];
    $highest = 0;
    $lowest = 0;
    if ($forecast_data){
      // Create a table of the weather forecast as a render array. First loop
      // over the forecast data and create rows for the table.
      foreach ($forecast_data as $item) {
        [
          'weekday' => $weekday,
          'description' => $description,
          'high' => $high,
          'low' => $low,
          'icon' => $icon,
        ] = $item;

        $table_rows[] = [
          // Simple text for a cell can be added directly to the array.
          $weekday,
          [
            'data' => [
              '#markup' => '<img alt="' . $description . '" src="' . $icon . '" width="200" height="200"/>',
            ],
          ],
          [
            'data' => [
              '#markup' => $this->t('%description with a high of @high and a low of @low',
                [
                  '%description' => $description,
                  '@high' => $high,
                  '%low' => $low,
                ]
              ),
            ],
          ],
        ];

        $highest = max($highest, $high);
        $lowest = min($lowest, $low);
      }

      // Extended forecast as a table.
      $weather_forecast = [
        '#type' => 'table',
        '#header' => [
          'Day',
          '',
          'Forecast',
        ],
        '#rows' => $table_rows,
        '#attributes' => [
          'class' => ['weather_page--forecast-table'],
        ]
      ];

      // Summary forecast.
      $short_forecast = [
        '#type' => 'markup',
        '#markup' => '<p>' . $this->t('The high for the weekend is @highest and the low is @lowest.',
            [
              '@highest' => $highest,
              '@lowest' => $lowest,
            ]
          ) . '</p>',
      ];

    }
    else {
      // Or display a message if we can't get the current forecast.
      $weather_forecast = ['#markup' => '<p>'. $this->t('Could not get the weather forecast. Dress for anything.') . '</p>'];
      $short_forecast = NULL;
    }

    $build = [
      // which theme hook to use for this content. See anytown_theme().
      '#theme' => 'weather_page',
      // Attached the css and JS for the page
      '#attached' => [
        'library' => ['anytown/forecast'],
      ],
      // when passing a render array to twig template file any top level array
      // element that starts with a '#' will be a variable in the template file.
      // example: {{ weather_intro }}.
      '#weather_intro' => [
        '#markup' => "<p>" . $this->t("Check out this weekend's weather forecast and come prepared. Market is mostly outside, and takes place rain or shine.") . "</p>",
      ],
      '#weather_forecast' => $weather_forecast,
      '#short_forecast' => $short_forecast,
      '#weather_closures' => [
        '#theme' => 'item_list',
        '#title' => $this->t('Weather related closures'),
        '#items' => [
          $this->t('Ice rink closed until winter - please stay off while we prepare it.'),
          $this->t('Parking behind Apple Lane is still closed from all the rain last weekend.')
        ],
      ],
    ];

    return $build;
  }
}
