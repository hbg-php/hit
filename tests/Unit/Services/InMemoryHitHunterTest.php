<?php

use Hit\Services\InMemoryHitHunter;

it('does not detect issues', function (): void {
    $spellchecker = InMemoryHitHunter::default();

    $issues = $spellchecker->hit('Hello viewers');

    expect($issues)->toBeEmpty();
});

it('detects issues', function (): void {
    $spellchecker = InMemoryHitHunter::default();

    $issues = $spellchecker->hit('Hello viewerss');

    expect($issues)->toHaveCount(1)
        ->and($issues[0]->word)->toBe('viewerss')
        ->and($issues[0]->suggestions)->toBe([
            'viewers',
            'viewer\'s',
            'viewer',
            'viewed',
        ]);
});
