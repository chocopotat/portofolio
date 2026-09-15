# 📡 Contact Form API Reference

Complete API documentation for the portfolio contact form submission endpoint.

---

## Endpoint Overview

```
POST /api/contact
```

**Purpose**: Receive and process contact form submissions with validation, rate limiting, and email notifications.

**Response Type**: JSON

**Authentication**: None (public endpoint)

**Rate Limit**: 3 requests per 60 seconds per IP address

---

## Request

### Headers

```http
Content-Type: application/json
X-CSRF-TOKEN: <token-from-form>
```

### Body

```json
{
  "name": "John Doe",
  "email": "john@example.com",
  "subject": "Project Inquiry",
  "message": "I'm interested in discussing a collaboration..."
}
```

### Field Specifications

| Field | Type | Required | Constraints |
|-------|------|----------|-------------|
| `name` | string | Yes | 2-100 chars, letters/spaces/hyphens/apostrophes only |
| `email` | string | Yes | Valid email format, DNS validation, max 255 chars |
| `subject` | string | Yes | 5-200 chars, no HTML tags |
| `message` | string | Yes | 10-5000 chars, no HTML tags |

---

## Success Response

### Status Code: 200 OK

```json
{
  "success": true,
  "message": "Message sent successfully! I'll get back to you within 24 hours."
}
```

---

## Error Responses

### 1. Validation Error - Status: 422

**Trigger**: One or more fields fail validation

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "name": [
      "Name can only contain letters, spaces, hyphens, and apostrophes"
    ],
    "email": [
      "Please provide a valid email address"
    ],
    "subject": [
      "Subject must be at least 5 characters"
    ],
    "message": [
      "Message must be at least 10 characters"
    ]
  }
}
```

### 2. Rate Limit Exceeded - Status: 429

**Trigger**: More than 3 submissions from same IP in 60 seconds

```json
{
  "success": false,
  "message": "Please wait before sending another message"
}
```

### 3. Server Error - Status: 500

**Trigger**: Mail sending fails or internal server error

```json
{
  "success": false,
  "message": "Error sending message. Please try again later."
}
```

---

## Client-Side Implementation

### Using Fetch API (Vanilla JavaScript)

```javascript
async function submitContactForm(formData) {
  try {
    const response = await fetch('/api/contact', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(formData)
    });

    const data = await response.json();

    if (response.ok && data.success) {
      console.log('✅ Success:', data.message);
      // Show success message to user
      // Clear form
    } else {
      console.error('❌ Error:', data.errors || data.message);
      // Show error message to user
      // Highlight invalid fields
    }
  } catch (error) {
    console.error('❌ Network error:', error.message);
    // Show network error message
  }
}
```

### Using Alpine.js (Current Implementation)

```javascript
// In resources/js/app.js or in a <script> tag
document.addEventListener('alpine:init', () => {
  Alpine.data('contactForm', () => ({
    form: {
      name: '',
      email: '',
      subject: '',
      message: ''
    },
    loading: false,
    message: '',
    success: false,

    async handleSubmit() {
      this.loading = true;
      this.message = '';

      try {
        const response = await fetch('/api/contact', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify(this.form)
        });

        const data = await response.json();

        if (data.success) {
          this.success = true;
          this.message = data.message;
          
          // Reset form
          this.form = {
            name: '',
            email: '',
            subject: '',
            message: ''
          };

          // Hide message after 5 seconds
          setTimeout(() => {
            this.success = false;
            this.message = '';
          }, 5000);
        } else {
          this.success = false;
          this.message = data.message || 'Something went wrong';
        }
      } catch (error) {
        this.success = false;
        this.message = 'Network error. Please try again.';
      } finally {
        this.loading = false;
      }
    }
  }));
});
```

### Using Axios (Vue/React)

```javascript
import axios from 'axios';

const submitForm = async (formData) => {
  try {
    const response = await axios.post('/api/contact', formData, {
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      }
    });

    console.log('✅ Success:', response.data.message);
  } catch (error) {
    if (error.response?.status === 422) {
      console.error('Validation errors:', error.response.data.errors);
    } else if (error.response?.status === 429) {
      console.error('Rate limited:', error.response.data.message);
    } else {
      console.error('Error:', error.message);
    }
  }
};
```

---

## Testing the API

### cURL Request

```bash
curl -X POST http://localhost:8000/api/contact \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Smith",
    "email": "jane@example.com",
    "subject": "Great work on your portfolio!",
    "message": "I really enjoyed viewing your projects and would love to collaborate."
  }'
```

### Thunder Client / Postman

1. **Create Request**
   - Method: POST
   - URL: `http://localhost:8000/api/contact`

2. **Headers**
   - Content-Type: application/json

3. **Body (JSON)**
   ```json
   {
     "name": "Jane Smith",
     "email": "jane@example.com",
     "subject": "Project Collaboration",
     "message": "Interested in working together on an exciting project."
   }
   ```

4. **Send Request**

### Using Laravel Tinker

```bash
php artisan tinker

# Send POST request
$response = Illuminate\Support\Facades\Http::post('/api/contact', [
    'name' => 'Test User',
    'email' => 'test@example.com',
    'subject' => 'Test Subject',
    'message' => 'This is a test message for the API.'
]);

$response->json();
```

---

## Rate Limiting Details

### How It Works

- **Limit**: 3 requests per 60 seconds
- **Key**: IP address of requester
- **Storage**: Laravel cache (configurable in `.env`)
- **Retry-After**: 60 seconds (included in response headers)

### Cache Key Format

```
portfolio_contact_{IP_ADDRESS}
```

Example: `portfolio_contact_192.168.1.100`

### Adjusting Rate Limit

To change the rate limit, modify `routes/web.php`:

```php
// Current: 3 requests per 60 seconds
Route::post('/api/contact', [PortfolioController::class, 'submitContact'])->middleware('throttle:3,60');

// More permissive: 10 requests per 60 seconds
Route::post('/api/contact', [PortfolioController::class, 'submitContact'])->middleware('throttle:10,60');

// More restrictive: 1 request per 60 seconds
Route::post('/api/contact', [PortfolioController::class, 'submitContact'])->middleware('throttle:1,60');
```

---

## Validation Rules

### Name Validation

```regex
^[\pL\s\'-]+$
```

**Matches**: Letters, spaces, hyphens, apostrophes (supports Unicode)

**Examples**:
- ✅ John Doe
- ✅ Marie-Claire
- ✅ José María
- ✅ O'Connor
- ❌ John123 (numbers not allowed)
- ❌ John@Doe (special chars not allowed)

### Email Validation

- RFC standard email format
- DNS validation enabled
- Examples:
  - ✅ john@example.com
  - ✅ john.doe@subdomain.example.co.uk
  - ❌ john@invalid (no TLD)
  - ❌ @example.com (no local part)

### Subject Validation

- 5-200 characters
- HTML tags stripped
- Examples:
  - ✅ "Project Inquiry"
  - ✅ "Feedback on your portfolio"
  - ❌ "Hi" (too short)
  - ❌ "A".repeat(201) (too long)

### Message Validation

- 10-5000 characters
- HTML tags stripped (XSS prevention)
- Examples:
  - ✅ "I'm interested in discussing..."
  - ✅ Multi-paragraph message
  - ❌ "Hi there" (too short)
  - ❌ Extremely long message > 5000 chars

---

## Server-Side Implementation (Laravel)

### Controller Method

File: `app/Http/Controllers/PortfolioController.php`

```php
public function submitContact(Request $request)
{
    // Validate input
    $validated = $request->validate([
        'name' => 'required|string|min:2|max:100|regex:/^[\pL\s\'-]+$/u',
        'email' => 'required|email:rfc,dns|max:255',
        'subject' => 'required|string|min:5|max:200',
        'message' => 'required|string|min:10|max:5000',
    ]);

    // Sanitize input (prevent XSS)
    $validated['name'] = strip_tags($validated['name']);
    $validated['email'] = strtolower(trim($validated['email']));
    $validated['subject'] = strip_tags($validated['subject']);
    $validated['message'] = strip_tags($validated['message']);

    // Send emails...
    // Return response...
}
```

### Route Definition

File: `routes/web.php`

```php
Route::post('/api/contact', [PortfolioController::class, 'submitContact'])
    ->middleware('throttle:3,60')
    ->name('contact.submit');
```

---

## Email Notifications

### Admin Notification Email

Sent to: `config('app.contact_email')`

```
To: hello@portfolio.local
Subject: Portfolio Contact: [User's Subject]

Body:
Name: [User Name]
Email: [User Email]
Subject: [User Subject]

Message:
[User Message]

---
Reply to this email to contact [User Name]
```

### User Confirmation Email

Sent to: `[Submitter's Email]`

```
Subject: Thank you for reaching out!

Body:
Hi [User Name],

Thank you for your message! I've received your inquiry and 
will get back to you within 24 hours.

[Personalized message about response time expectations]

Best regards,
Portfolio Team
```

---

## Troubleshooting

### "Please wait before sending another message"

**Cause**: Rate limit exceeded (3+ requests in 60 seconds)

**Solution**: 
- Wait 60 seconds before submitting again
- Check that form isn't being submitted multiple times
- Clear browser cache if stuck

### Validation Error: Email Invalid

**Possible Causes**:
- Invalid email format
- Email domain doesn't exist
- DNS lookup failed

**Solution**:
- Use a valid email address
- Check email has proper format: `user@domain.com`
- Ensure internet connection for DNS lookup

### "Error sending message"

**Cause**: Mail service configuration issue

**Solution**:
- Check `.env` mail configuration
- Verify SMTP credentials
- Check mail logs: `tail -f storage/logs/laravel.log`
- Test with `php artisan tinker`

### Response Timeout

**Cause**: Slow server, mail service delay

**Solution**:
- Increase request timeout in browser
- Check server resources
- Contact hosting provider
- Consider async email sending

---

## Performance Optimization

### For High Volume Submissions

**Queue Mail Sending** (Process in background):

```php
// In .env
QUEUE_CONNECTION=database

// In routes/web.php
Mail::queue('emails.contact-notification', $data, function($mail) {
    // email configuration
});
```

**Database Storage**:

```php
// Store submissions in database
ContactSubmission::create($validated);
```

---

## Security Considerations

✅ **Implemented**:
- CSRF token verification
- Input validation (whitelist approach)
- XSS prevention (strip_tags)
- Rate limiting
- Email validation with DNS

✅ **Additional**: 
- Never expose database in logs
- Use environment variables for credentials
- Keep error messages user-friendly
- Monitor for suspicious patterns
- Regular security updates

---

## Future Enhancements

- [ ] Honeypot field (spam prevention)
- [ ] reCAPTCHA integration
- [ ] Phone number field
- [ ] File attachments
- [ ] Project type selection
- [ ] Budget range slider
- [ ] Automatic response delay (anti-bot)
- [ ] Admin dashboard for submissions
- [ ] Email templates customization
- [ ] Analytics/reporting

---

**Last Updated**: 2024
**API Version**: 1.0
**Status**: Production Ready ✅
