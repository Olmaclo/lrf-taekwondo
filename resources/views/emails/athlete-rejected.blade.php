<x-mail::message>
# Inscription refusée

Bonjour **{{ $athlete->coach?->name ?? 'Coach' }}**,

Nous vous informons que l'inscription de votre athlète a été **refusée** par l'équipe technique de la Ligue.

---

**Athlète :** {{ $athlete->full_name }}
**Événement :** {{ $athlete->event?->name ?? '—' }}
**Club :** {{ $athlete->club }}

@if($athlete->rejection_reason)
**Motif du refus :**
{{ $athlete->rejection_reason }}
@endif

---

Si vous pensez qu'il s'agit d'une erreur ou souhaitez obtenir des informations complémentaires, veuillez contacter l'équipe technique de la Ligue.

Cordialement,
**L'équipe de la Ligue Régionale de Fatick · Taekwondo**
</x-mail::message>
