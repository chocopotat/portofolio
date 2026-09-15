@component('mail::message')
# New Portfolio Contact Message

Hello! You've received a new message from your portfolio contact form.

## Message Details

**From:** {{ $name }}
**Email:** [{{ $email }}](mailto:{{ $email }})
**Subject:** {{ $subject }}

---

## Message

{{ $message }}

---

## Reply

To reply to this message, simply click the button below or reply to this email:

@component('mail::button', ['url' => 'mailto:' . $email])
Reply to Message
@endcomponent

---

Thanks for connecting!

**Portfolio Contact System**
@endcomponent
