# Implementation Summary: US18 & US19

## US18: Cập nhật thông tin cá nhân (Update Personal Information)

### Files Created/Modified:

1. **app/Controllers/ProfileController.php** (NEW)
   - `show()` - Display user profile form
   - `update()` - Handle profile update with validation

2. **views/users/profile.php** (NEW)
   - Bootstrap form for updating profile
   - Fields: Name, Email, Phone, Address
   - Error and success messages

3. **app/Models/User.php** (UPDATED)
   - `findById($id)` - Get user by ID
   - `update($id, $name, $email, $phone, $address)` - Update user info

4. **routes/web.php** (UPDATED)
   - `GET /profile` - Show profile form
   - `POST /profile` - Handle update

5. **views/partials/header.blade.php** (UPDATED)
   - Added profile and logout links for logged-in users

6. **views/users/login.php** (UPDATED)
   - Added forgot password link

### Features:
- Users can view and edit their profile information
- Email duplication check (except current user's email)
- Session update after profile change
- Bootstrap-styled responsive form

---

## US19: Quên mật khẩu (Forgot Password)

### Files Created/Modified:

1. **views/users/forgot-password.php** (NEW)
   - Email input form for password reset request
   - Success message showing after submission

2. **views/users/reset-password.php** (NEW)
   - Password reset form with validation
   - Requires valid reset token
   - Token expiry check (1 hour)

3. **app/Controllers/AuthController.php** (UPDATED)
   - `showForgotPassword()` - Display forgot password form
   - `forgotPassword()` - Generate reset token and send email
   - `showResetPassword($token)` - Display reset password form
   - `resetPassword($token)` - Handle password update

4. **app/Models/User.php** (UPDATED)
   - `setPasswordResetToken($email)` - Generate and store reset token (1 hour expiry)
   - `findByPasswordResetToken($token)` - Find user by valid reset token
   - `updatePassword($id, $newPassword)` - Update password with hashing
   - `clearPasswordResetToken($id)` - Clear token after successful reset

5. **routes/web.php** (UPDATED)
   - `GET /forgot-password` - Show forgot password form
   - `POST /forgot-password` - Handle email and send reset link
   - `GET /reset-password/:token` - Show reset password form
   - `POST /reset-password/:token` - Handle password reset

### Features:
- Users can request password reset via email
- Secure reset token with 1 hour expiry
- Email sending using PHP mail()
- Password validation (min 6 characters)
- Automatic session redirect after successful reset
- Token validation before allowing password change

---

## Database Changes Required

Run the SQL in `DATABASE_MIGRATION.sql`:

```sql
ALTER TABLE users ADD COLUMN phone VARCHAR(20) NULL AFTER password;
ALTER TABLE users ADD COLUMN address TEXT NULL AFTER phone;
ALTER TABLE users ADD COLUMN reset_token VARCHAR(255) NULL AFTER address;
ALTER TABLE users ADD COLUMN reset_token_expires DATETIME NULL AFTER reset_token;
```

## Email Configuration

The password reset emails are sent using PHP's `mail()` function. For production:
- Configure a proper SMTP service (PHPMailer, Symfony Mailer, etc.)
- Update the email address in AuthController::forgotPassword()
- Ensure your server's mail() function is properly configured

## Testing

1. **Profile Update (US18)**:
   - Go to `/Agile-1-VPP/profile` (requires login)
   - Update name, email, phone, or address
   - Changes should reflect immediately

2. **Forgot Password (US19)**:
   - Go to `/Agile-1-VPP/login`
   - Click "Quên mật khẩu?" (Forgot Password)
   - Enter email address
   - Check email for reset link
   - Click link (format: `/reset-password/TOKEN`)
   - Enter new password
   - Should redirect to login after successful reset

## Security Notes

- Passwords are hashed using PASSWORD_DEFAULT (bcrypt)
- Reset tokens are random 64-character hex strings
- Tokens expire after 1 hour
- Email addresses are validated before and after update
- Session is updated immediately after profile changes
