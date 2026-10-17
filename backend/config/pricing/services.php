<?php

declare(strict_types=1);

/*
 * Every service the storefront sells: one list for the catalog, the quote engine and order validation.
 *
 * `strategy` picks how App\Pricing prices it, `table` is the price table under config/pricing, `amount`
 * bounds the wins or games slider when the strategy has one, and `defaults` is what the order builder starts on. Options add (or remove) a percentage of the
 * price. Their percentages come from frontend/assets/js/<service>/options.js (legacy).
 *
 * The keys are the legacy URLs (frontend/config/router.js), so /division-boosting keeps working.
 */

$option = function (string $key, int $percent): array {
	$options = [
		'appear-offline' => ['Appear offline on chat', 'Your friends won\'t see you online while the booster plays on your account.'],
		'specific-champions' => ['Specific champions or roles', 'Choose the roles (or champions) your booster plays.'],
		'specific-agents' => ['Specific agents', 'Choose the agents your booster plays.'],
		'priority' => ['Priority order', 'Your order goes to the front of the queue and is played faster.'],
		'streaming' => ['Streaming', 'Your booster streams or records every game.'],
		'extra-win' => ['+1 win in your new rank', 'One extra net win once you reach the rank you ordered.'],
		'coaching' => ['Coaching', 'Talk with your booster in a voice call during and between games.'],
		'high-mmr' => ['High-MMR account', 'Your booster duos with you from a high-MMR account.'],
		'play-with-booster' => ['Play with your booster', 'Your booster plays alongside you from their own account.'],
		'games-instead-of-wins' => ['Games instead of wins', 'Each game counts, won or lost.'],
		'net-wins' => ['Net wins', 'Every loss adds one more win to the order.'],
	];

	return ['key' => $key, 'label' => $options[$key][0], 'description' => $options[$key][1], 'percent' => $percent];
};

return [
	'division-boosting' => [
		'game' => 'lol',
		'label' => 'Division boosting',
		'strategy' => 'division-ladder',
		'table' => 'lol.ladder',
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'III'], 'lp' => '21-40', 'mmr' => '15-17'],
		'options' => [$option('appear-offline', 0), $option('specific-champions', 0), $option('priority', 20), $option('streaming', 15), $option('extra-win', 0)],
	],
	'duoqueue-boosting' => [
		'game' => 'lol',
		'label' => 'Duo queue boosting',
		'strategy' => 'division-ladder',
		'table' => 'lol.ladder',
		// The booster plays with you, which takes longer
		'multiplier_pct' => 150,
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'III'], 'lp' => '21-40', 'mmr' => '15-17'],
		'options' => [$option('coaching', 30), $option('priority', 20), $option('high-mmr', 20)],
	],
	'placement-boosting' => [
		'game' => 'lol',
		'label' => 'Placement games',
		'strategy' => 'per-game',
		'table' => 'lol.placements',
		'amount' => ['label' => 'games', 'min' => 1, 'max' => 10, 'default' => 10],
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'amount' => 10],
		'options' => [$option('play-with-booster', 35), $option('appear-offline', 0), $option('specific-champions', 0), $option('priority', 20), $option('streaming', 15)],
	],
	'ranked-wins-boosting' => [
		'game' => 'lol',
		'label' => 'Ranked wins',
		'strategy' => 'per-win',
		'table' => 'lol.wins',
		'amount' => ['label' => 'wins', 'min' => 1, 'max' => 10, 'default' => 4],
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'amount' => 4],
		'options' => [$option('play-with-booster', 40), $option('appear-offline', 0), $option('specific-champions', 0), $option('games-instead-of-wins', -30), $option('priority', 20), $option('streaming', 15)],
	],
	'champion-mastery' => [
		'game' => 'lol',
		'label' => 'Champion mastery',
		'strategy' => 'mastery',
		'table' => 'lol.mastery',
		'defaults' => ['champion' => 'Aatrox', 'level' => ['current' => 5, 'desired' => 7], 'tokens' => 5],
		'options' => [$option('appear-offline', 0), $option('priority', 20), $option('streaming', 15)],
	],
	'valorant-boosting' => [
		'game' => 'valorant',
		'label' => 'Rank boosting',
		'strategy' => 'division-ladder',
		'table' => 'valorant.ladder',
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'III']],
		'options' => [$option('appear-offline', 0), $option('specific-agents', 0), $option('priority', 20), $option('streaming', 15), $option('extra-win', 0)],
	],
	'valorant-placement-boosting' => [
		'game' => 'valorant',
		'label' => 'Placement games',
		'strategy' => 'per-game',
		'table' => 'valorant.placements',
		'amount' => ['label' => 'games', 'min' => 1, 'max' => 3, 'default' => 3],
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'amount' => 3],
		'options' => [$option('appear-offline', 0), $option('specific-agents', 0), $option('play-with-booster', 50), $option('priority', 20), $option('streaming', 15)],
	],
	'unrated-matches' => [
		'game' => 'valorant',
		'label' => 'Unrated matches',
		'strategy' => 'unrated',
		'table' => 'valorant.unrated',
		'amount' => ['label' => 'games', 'min' => 1, 'max' => 30, 'default' => 5],
		'defaults' => ['amount' => 5],
		'options' => [$option('appear-offline', 0), $option('specific-agents', 0), $option('net-wins', 40), $option('play-with-booster', 40), $option('priority', 20), $option('streaming', 15)],
	],
	'wildrift-rank-boosting' => [
		'game' => 'wildrift',
		'label' => 'Rank boosting',
		'strategy' => 'division-ladder',
		'table' => 'wildrift.ladder',
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'desired' => ['tier' => 'gold', 'division' => 'III']],
		'options' => [$option('appear-offline', 0), $option('specific-champions', 0), $option('priority', 20), $option('streaming', 15), $option('extra-win', 0)],
	],
	'wildrift-placement-boosting' => [
		'game' => 'wildrift',
		'label' => 'Placement games',
		'strategy' => 'per-game',
		'table' => 'wildrift.placements',
		'amount' => ['label' => 'games', 'min' => 1, 'max' => 10, 'default' => 5],
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'amount' => 5],
		'options' => [$option('play-with-booster', 35), $option('appear-offline', 0), $option('specific-champions', 0), $option('priority', 20), $option('streaming', 15)],
	],
	'wildrift-ranked-wins-boosting' => [
		'game' => 'wildrift',
		'label' => 'Ranked wins',
		'strategy' => 'per-win',
		'table' => 'wildrift.wins',
		'amount' => ['label' => 'wins', 'min' => 1, 'max' => 10, 'default' => 4],
		'defaults' => ['current' => ['tier' => 'bronze', 'division' => 'I'], 'amount' => 4],
		'options' => [$option('play-with-booster', 40), $option('appear-offline', 0), $option('specific-champions', 0), $option('games-instead-of-wins', -30), $option('priority', 20), $option('streaming', 15)],
	],
];
