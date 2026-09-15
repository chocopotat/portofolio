@component('mail::message')
# Thank you for reaching out! 👋

Hi {{ $name }},

Thank you for contacting me through my portfolio. I've received your message and I'll get back to you as soon as possible, typically within 24 hours.

In the meantime, feel free to:
- Check out my latest projects
- Review my tech stack and experience
- Connect with me on social media

Looking forward to connecting with you!

---

**Best regards,**
**Fullstack Developer**

@component('mail::button', ['url' => url('/')])
Back to Portfolio
@endcomponent

---

*This is an automated confirmation. Please do not reply to this email. Your original message has been recorded.*
@endcomponent
