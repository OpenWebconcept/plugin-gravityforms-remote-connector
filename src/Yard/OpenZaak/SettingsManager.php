<?php

namespace Yard\OpenZaak;

use Yard\Foundation\SettingsManager as BaseSettingsManager;

class SettingsManager extends BaseSettingsManager
{
    /**
     * OpenZaak settings key.
     *
     * @var string
     */
    protected $key = 'gravityformsaddon_openzaak-addon_settings';

    /**
     * Get the RSIN code.
     *`.
     *
     * @return string|null
     */
    public function getRISN(): ?string
    {
        return $this->get('openzaak_rsin', null);
    }
}
