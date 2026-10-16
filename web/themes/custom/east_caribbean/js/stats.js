(function (Drupal, drupalSettings) {
    'use strict';

    Drupal.behaviors.eczUserStats = {
        attach: function (context, settings) {

            const statsContainer = context.querySelector('#ecz-user-stats');
            if (!statsContainer || statsContainer.dataset.initialized) {
                return;
            }
            statsContainer.dataset.initialized = 'true';

            const cid = drupalSettings.eczUser.cid;
            if (!cid) {
                statsContainer.innerHTML = `
                    <div class="ecz-stat-item text-center text-muted">
                        No VATSIM CID found on your profile.
                    </div>
                `;
                return;
            }

            statsContainer.innerHTML = `
                <div class="ecz-stat-item text-center text-muted" style="border-style:dashed;">
                    Loading your stats...
                </div>
            `;

            async function fetchStats() {
                try {
                    const response = await fetch(`/api/vatsim/atc/${cid}`);
                    if (!response.ok) throw new Error("Network response was not ok");

                    const stats = await response.json();

                    statsContainer.innerHTML = `
                        <div class="ecz-stat-item">
                            Hours Controlled (30d): <strong>${stats.hours_30d}</strong>
                        </div>
                        <div class="ecz-stat-item">
                            Sessions This Week: <strong>${stats.sessions_week}</strong>
                        </div>
                        <div class="ecz-stat-item">
                            Top Position: <strong>${stats.top_position ?? 'N/A'}</strong>
                        </div>
                    `;
                } catch (error) {
                    console.error("Error fetching VATSIM stats:", error);
                    statsContainer.innerHTML = `
                        <div class="ecz-stat-item text-center" style="color:#e11d48;">
                            Failed to load stats.
                        </div>
                    `;
                }
            }

            fetchStats();
        }
    };
})(Drupal, drupalSettings);
