<x-mail::message>
# Votre compte a été approuvé

Bonjour **{{ $coach->name }}**,

Bonne nouvelle ! Votre compte coach sur la plateforme de la **Ligue Régionale de Fatick · Taekwondo** a été **approuvé** par l'équipe d'administration.

Vous pouvez désormais vous connecter et inscrire vos athlètes aux compétitions ouvertes.

<x-mail::button :url="config('app.url') . '/inscription'">
Accéder à la plateforme
</x-mail::button>

---

**Club :** {{ $coach->club ?? '—' }}

Si vous rencontrez des difficultés pour vous connecter, utilisez la fonction "Mot de passe oublié" sur la page de connexion.

Cordialement,
**L'équipe de la Ligue Régionale de Fatick · Taekwondo**
</x-mail::message>
