<?php

namespace App\Traits;

trait ValidationRules
{
    /**
     * Règle pour un nom sans chiffres
     */
    public static $nameRule = 'required|string|max:255|regex:/^[a-zA-ZÀ-ÿ\s\-]+$/u';

    /**
     * Règle pour un mot de passe fort
     */
    public static $passwordRule = 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/';

    /**
     * Règle pour une date de naissance valide
     */
    public static $dateNaissanceRule = 'required|date|before:today|after:1900-01-01';

    /**
     * Règle pour une date dans le futur (notification)
     */
    public static $dateFutureRule = 'required|date|after_or_equal:today';
}