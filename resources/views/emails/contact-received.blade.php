<h2>Nova kontakt poruka</h2>

<p><strong>Ime:</strong> {{ $contactMessage->name }}</p>
<p><strong>Email:</strong> {{ $contactMessage->email }}</p>
<p><strong>Predmet:</strong> {{ $contactMessage->subject }}</p>

<hr>

<p>{!! nl2br(e($contactMessage->message)) !!}</p>

<hr>
<p><small>Poslano putem kontakt obrasca na web stranici.</small></p>
