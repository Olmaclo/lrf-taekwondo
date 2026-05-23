<x-mail::message>
# Nouveau coach en attente de validation

Un nouveau coach vient de s'inscrire sur la plateforme et attend votre validation.

---

**Nom :** {{ $coach->name }}
**Email :** {{ $coach->email }}
**Club :** {{ $coach->club ?? '—' }}
**Numéro de licence :** {{ $coach->license_number ?? '—' }}
**Code fédéral :** {{ $coach->federal_code ?? '—' }}
**Inscrit le :** {{ $coach->created_at->format('d/m/Y à H:i') }}

---

<x-mail::button :url="config('app.url') . '/dashboard'">
Valider depuis le tableau de bord
</x-mail::button>

Cordialement,
**Système de notification automatique — LRF Taekwondo**
</x-mail::message>
