<h2>New contact message</h2>

<p><strong>Name:</strong> {{ $contactMessage->name }}</p>
<p><strong>Email:</strong> {{ $contactMessage->email }}</p>
@if($contactMessage->subject)
<p><strong>Subject:</strong> {{ $contactMessage->subject }}</p>
@endif

<p><strong>Message:</strong></p>
<pre style="white-space: pre-wrap; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, 'Liberation Mono', 'Courier New', monospace;">{{ $contactMessage->message }}</pre>

