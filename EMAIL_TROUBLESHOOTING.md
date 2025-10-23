# Password Reset Email Troubleshooting Guide

## Issue: Not Receiving Password Reset Emails

### Quick Test
1. Visit: `http://localhost/jacarandalibraryms/test-email.php` (or your server URL)
2. This will test the SMTP connection and show detailed debug information
3. Check if you receive a test email at: `lettherebecarnagex@gmail.com`

---

## Common Causes & Solutions

### 1. Gmail App Password Issues
**The most common issue** - Gmail's security settings

#### Solution:
1. Go to your Google Account: https://myaccount.google.com/
2. Enable 2-Factor Authentication if not already enabled
3. Generate a new App Password:
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (Custom name)"
   - Name it "Jacaranda Library"
   - Copy the 16-character password (e.g., `wiyc axrx gdvj poxv`)
4. Update `config/config.php` with the new app password:
   ```php
   define('SMTP_PASSWORD', 'your-new-app-password');
   ```

### 2. Gmail "Less Secure Apps" Setting
If you're not using an app password:

#### Solution:
- **Recommended:** Use App Passwords instead (see #1)
- **Alternative:** Enable "Less secure app access" (not recommended):
  - https://myaccount.google.com/lesssecureapps

### 3. Spam/Junk Folder
The email might be delivered but filtered as spam.

#### Solution:
- Check your spam/junk folder
- Mark the email as "Not Spam"
- Add `lettherebecarnagex@gmail.com` to your contacts

### 4. SMTP Connection Blocked
Your hosting provider or firewall might block SMTP connections.

#### Solution:
- Run the test script: `test-email.php`
- Check for error messages like "Connection refused" or "Timeout"
- Contact your hosting provider if needed

### 5. Wrong Email Address
You might have entered a typo when requesting the reset.

#### Solution:
- Try again with the correct email address
- Verify the email matches your account exactly

---

## Debugging Steps

### Step 1: Run the Email Test
```
Visit: http://localhost/jacarandalibraryms/test-email.php
```

### Step 2: Check PHP Error Logs
Look for error messages in XAMPP logs:
- `C:\xampp\apache\logs\error.log`
- `C:\xampp\php\logs\php_error_log`

### Step 3: Enable SMTP Debug Mode
Temporarily edit `app/core/Mailer.php` to see detailed errors:

```php
public function __construct() {
    $this->mailer = new PHPMailer(true);
    
    // Add this line for debugging:
    $this->mailer->SMTPDebug = SMTP::DEBUG_SERVER;
    
    // ... rest of code
}
```

### Step 4: Check Database
Verify the reset token was created:
```sql
SELECT * FROM password_resets WHERE email = 'your-email@example.com';
```

---

## Alternative Email Providers

If Gmail continues to have issues, consider these alternatives:

### 1. Mailtrap (Development/Testing)
```php
define('SMTP_HOST', 'smtp.mailtrap.io');
define('SMTP_PORT', 2525);
define('SMTP_USERNAME', 'your-mailtrap-username');
define('SMTP_PASSWORD', 'your-mailtrap-password');
```

### 2. SendGrid (Production)
```php
define('SMTP_HOST', 'smtp.sendgrid.net');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'apikey');
define('SMTP_PASSWORD', 'your-sendgrid-api-key');
```

### 3. Mailgun (Production)
```php
define('SMTP_HOST', 'smtp.mailgun.org');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your-mailgun-username');
define('SMTP_PASSWORD', 'your-mailgun-password');
```

---

## Current Configuration
- **SMTP Host:** smtp.gmail.com
- **SMTP Port:** 587
- **SMTP Username:** lettherebecarnagex@gmail.com
- **Encryption:** STARTTLS

---

## Security Note
The current password reset system has good security features:
- ✓ CSRF protection
- ✓ Timing attack prevention
- ✓ 1-hour token expiration
- ✓ Generic success messages (doesn't reveal if email exists)
- ✓ Security logging

---

## Need More Help?

1. Run `test-email.php` and share the debug output
2. Check the Apache error log: `C:\xampp\apache\logs\error.log`
3. Verify your Gmail account settings
4. Try requesting a password reset with a different email address
