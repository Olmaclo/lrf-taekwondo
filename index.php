<?php

/**
 * Pont d'hébergement (Hostinger) — le DocRoot pointe sur la racine du projet.
 * On délègue au front controller Laravel situé dans public/.
 * Les chemins internes de public/index.php restent corrects (__DIR__ = .../public).
 */
require __DIR__.'/public/index.php';
