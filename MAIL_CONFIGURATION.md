# SMTP Configuration for Parent Registration

## Gmail SMTP Settings

Add these settings to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=drojodenaialam@gmail.com
MAIL_PASSWORD="halk xwhx gaov lkug"
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=drojodenaialam@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Alternative Configuration (SSL)

If you prefer to use SSL instead of TLS:

```env
MAIL_PORT=465
MAIL_ENCRYPTION=ssl
```

## Testing Email Configuration

Run this command to test if email is working:

```bash
php artisan tinker
```

Then in tinker:

```php
Mail::raw('Test email from Droplets Dojo', function($message) {
    $message->to('your-email@example.com')
            ->subject('Test Email');
});
```

## Important Notes

1. **App Password**: The password `halk xwhx gaov lkug` is a Gmail App Password, not the regular Gmail password
2. **2FA Required**: Gmail App Passwords only work if 2-Factor Authentication is enabled on the Gmail account
3. **Less Secure Apps**: Make sure "Less secure app access" is turned OFF (use App Password instead)
4. **Firewall**: Ensure ports 587 (TLS) or 465 (SSL) are not blocked by your firewall

## Features Implemented

✅ Parent registration form at `/parent/register`
✅ Email verification with secure token
✅ 24-hour expiration for registration links
✅ Beautiful email template
✅ Complete registration form with dojo selection
✅ Automatic parent role assignment
✅ Auto-login after registration

## Testing the Flow

1. Go to `/login`
2. Click "Register here" under "Are you a parent?"
3. Enter email address
4. Check email inbox for registration link
5. Click link to complete registration
6. Fill in all details and submit
7. Automatically logged in and redirected to parent dashboard

