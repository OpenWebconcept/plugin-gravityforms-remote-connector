<?php

namespace Yard\OpenZaak;

use Yard\Settings\SettingsManager;

class OpenZaakSettingsManager extends SettingsManager
{
    /**
     * OpenZaak settings key.
     *
     * @var string
     */
    protected $key = 'openzaak_settings';

    /**
     * Get the RSIN code.
     *`.
     *
     * @return string|null
     */
    public function getRISN(): ?string
    {
        return $this->settings['irma_wp_rsin'] ?? null;
    }

    /**
     * Get the create case URL.
     *`.
     *
     * @return string|null
     */
    public function createCaseURL(): ?string
    {
        return $this->settings['createCaseURL'] ?? null;
    }

    /**
     * Get the create case object URL.
     *`.
     *
     * @return string|null
     */
    public function createCaseObjectURL(): ?string
    {
        return $this->settings['createCaseObjectURL'] ?? null;
    }

    /**
     * Get the create case property URL.
     *`.
     *
     * @return string|null
     */
    public function createCasePropertyURL(): ?string
    {
        return $this->settings['createCasePropertyURL'] ?? null;
    }

    /**
     * Get the RSIN code.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->settings ?? [];
    }
}
