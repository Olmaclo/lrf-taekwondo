<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class NotDisposableEmail implements ValidationRule
{
    private const BLOCKED = [
        'mailinator.com','mailinator.net','mailinator.org',
        'guerrillamail.com','guerrillamail.info','guerrillamail.biz',
        'guerrillamail.de','guerrillamail.net','guerrillamail.org',
        'guerrillamailblock.com','grr.la','sharklasers.com','spam4.me',
        '10minutemail.com','10minutemail.net','10minutemail.org','10minutemail.co.uk',
        'tempmail.com','temp-mail.org','temp-mail.io','tempmail.net','tempmail.org',
        'throwaway.email','throwam.com',
        'yopmail.com','yopmail.fr','yopmail.net',
        'trashmail.com','trashmail.at','trashmail.io','trashmail.me',
        'trashmail.net','trashmail.org','trashmail.xyz',
        'dispostable.com','discard.email',
        'maildrop.cc','mailnull.com','mailnesia.com','mailtemp.info',
        'getairmail.com','fakeinbox.com',
        'spamgourmet.com','spamgourmet.net','spamgourmet.org',
        'spamfree24.org','spamfree.eu','spam.la',
        'tempr.email','tmpmail.net','tmpmail.org','tmpeml.com',
        'moakt.com','mohmal.com','mytrashmail.com',
        'crazymailing.com','firemailbox.club',
        'no-spam.ws','nowmymail.com','notmail.com',
        'mt2015.com','mt2016.com','mt2017.com',
    ];

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $domain = strtolower((string) substr(strrchr((string) $value, '@'), 1));

        if (in_array($domain, self::BLOCKED, true)) {
            $fail('Les adresses email temporaires ou jetables ne sont pas acceptées. Veuillez utiliser une adresse email personnelle ou professionnelle valide.');
        }
    }
}
