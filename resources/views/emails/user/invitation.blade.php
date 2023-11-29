<x-mail::message>
    <h3>Bonjour {{ $user->name ?? 'user' }}</h3>
    <p>Veuillez cliquer sur le lien pour activer votre compte sur {{ config('app.name') }}.</p>



    <p>{{ $invitation->invitation_token }}</p>
 

    <x-mail::button :url="$invitation->getLink()">
        Activer mon compte
    </x-mail::button>
 
    © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
</x-mail::message>
