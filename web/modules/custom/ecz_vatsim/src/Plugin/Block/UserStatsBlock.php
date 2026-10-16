<?php

namespace Drupal\ecz_vatsim\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "ecz_user_stats_block",
 *   admin_label = @Translation("ECZ User Stats")
 * )
 */
class UserStatsBlock extends BlockBase {

    public function build() {
        return [
            '#markup' => '
                <h3>Your Stats</h3>
                <div id="ecz-user-cid" data-cid="{{ user.field_vatsim_cid.value }}"></div>
                <div id="ecz-user-stats">
                    <div class="ecz-stat-item text-center text-muted" style="border-style:dashed;">
                        Loading your stats...
                    </div>
                </div>

            ',
            '#attached' => [
                'library' => [
                    'east_caribbean/stats',
                ],
            ],
        ];
    }
}
