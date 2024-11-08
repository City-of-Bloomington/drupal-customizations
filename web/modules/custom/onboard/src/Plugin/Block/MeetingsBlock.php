<?php
/**
 * @copyright 2017-2021 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt GNU/GPL, see LICENSE
 */
declare (strict_types=1);
namespace Drupal\onboard\Plugin\Block;

use Drupal\onboard\OnBoardService;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\Node;

/**
 * Displays upcoming meetings for a committee
 *
 * @Block(
 *   id = "onboard_meetings_block",
 *   admin_label = "Committee Meetings"
 * )
 */
class MeetingsBlock extends BlockBase implements BlockPluginInterface
{
    const DEFAULT_NUMDAYS   = 7;
    const DEFAULT_MAXEVENTS = 4;

    public function getCacheContexts()
    {
        return Cache::mergeContexts(parent::getCacheContexts(), ['url.path']);
    }

    public function build()
    {
        $node = \Drupal::routeMatch()->getParameter('node');
        if ($node && $node instanceof Node) {
            $settings  = \Drupal::config('onboard.settings');
            $fieldname = $settings->get('onboard_committee_field');
            $config    = $this->getConfiguration();

            $numdays   = !empty($config['numdays'  ]) ? (int)$config['numdays'  ] : self::DEFAULT_NUMDAYS;
            $maxevents = !empty($config['maxevents']) ? (int)$config['maxevents'] : self::DEFAULT_MAXEVENTS;

            if ($node->hasField( $fieldname)) {
                $id = $node->get($fieldname)->value;
                if ($id) {
                    $start = new \DateTime();
                    $end   = new \DateTime();
                    $end->add(new \DateInterval("P{$numdays}D"));

                    $meetings  = OnBoardService::meetings($id,  null, $start, $end, $maxevents);
                    $committee = OnBoardService::committee_info($id);
                    return [
                        '#theme'     => 'onboard_upcoming_meetings',
                        '#meetings'  => $meetings,
                        '#committee' => $committee,
                        '#nid'       => $node->id(),
                        '#cache'       => [
                            'max-age'  => 3600
                        ]
                    ];
                }
            }
        }
    }

    public function blockForm($form, FormStateInterface $form_state)
    {
        $form   = parent::blockForm($form, $form_state);
        $config = $this->getConfiguration();

        $form['onboard_meetings_numdays'] = [
            '#type'          => 'number',
            '#title'         => 'Number of days',
            '#description'   => 'Maximum number of days in the future to look for events.',
            '#default_value' => isset($config['numdays']) ? $config['numdays'] : self::DEFAULT_NUMDAYS
        ];
        $form['onboard_meetings_maxevents'] = [
            '#type'          => 'number',
            '#title'         => 'Max Events',
            '#description'   => 'Maximum number of events to show in the block',
            '#default_value' => isset($config['maxevents']) ? $config['maxevents'] : self::DEFAULT_MAXEVENTS
        ];
        return $form;
   }

    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['numdays'  ] = $form_state->getValue('onboard_meetings_numdays'  );
        $this->configuration['maxevents'] = $form_state->getValue('onboard_meetings_maxevents');
   }
}
