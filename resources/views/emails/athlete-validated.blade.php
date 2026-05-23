<x-mail::message>
# Inscription validée

Bonjour **{{ $athlete->coach?->name ?? 'Coach' }}**,

L'inscription de votre athlète a été **validée** par l'équipe technique de la Ligue.

---

**Athlète :** {{ $athlete->full_name }}
**Événement :** {{ $athlete->event?->name ?? '—' }}
**Catégorie :** {{ $athlete->age_category }} {{ $athlete->gender === 'M' ? '(Masculin)' : '(Féminin)' }} — {{ $athlete->weight_category }}
**Club :** {{ $athlete->club }}
@if($athlete->license_number)
**Numéro de licence :** {{ $athlete->license_number }}
@endif

---

Vous pouvez vérifier le statut de vos athlètes à tout moment depuis la plateforme.

<x-mail::button :url="config('app.url') . '/verifier-inscription'">
Vérifier l'inscription
</x-mail::button>

Cordialement,
**L'équipe de la Ligue Régionale de Fatick · Taekwondo**
</x-mail::message>
