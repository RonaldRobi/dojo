# 🔐 Login Credentials - Droplets Dojo

> **Important**: This file contains sensitive information. Keep it secure and do not commit to public repositories.

---

## 📋 Available User Roles

### 1. **Super Admin** 🔑
Full system access with all permissions.

```
Email: admin@dojo.com
Password: password
```

**Capabilities**:
- ✅ Manage all dojos
- ✅ Manage all users
- ✅ System-wide configuration
- ✅ View all reports and analytics
- ✅ Access to admin panel

---

### 2. **Owner** 🏢
Dojo owner with management capabilities.

```
Email: owner@dojo.com
Password: password
```

**Capabilities**:
- ✅ Manage dojo settings
- ✅ Manage members and instructors
- ✅ View financial reports
- ✅ Manage classes and schedules
- ✅ Send announcements

---

### 3. **Coach** 👨‍🏫
Instructor/Coach role for teaching and student management.

```
Email: coach@dojo.com
Password: password
```

**Capabilities**:
- ✅ View assigned classes
- ✅ Manage student progress
- ✅ Record belt promotions
- ✅ Take attendance
- ✅ View student profiles

---

### 4. **Student** 🎓
Student account with access to personal progress and classes.

```
Email: student@dojo.com
Password: password
```

**Capabilities**:
- ✅ View class schedules
- ✅ Track belt progress
- ✅ View announcements
- ✅ Check payment history
- ✅ Access personal profile

---

### 5. **Parent** 👨‍👩‍👧
Parent account to manage children's activities.

```
Email: parent@dojo.com
Password: password
```

**Capabilities**:
- ✅ Register children
- ✅ View children's progress
- ✅ Manage payments
- ✅ View class schedules
- ✅ Receive event notifications

---

## 🚀 Quick Start

1. Navigate to login page: `http://your-domain.com/login`
2. Enter email and password from the list above
3. Click "Sign in"

---

## 🔒 Security Notes

- **Default Password**: All demo accounts use `password` as the default password
- **Production**: Change all passwords before deploying to production
- **Best Practice**: Use strong, unique passwords for each account
- **Two-Factor Auth**: Consider implementing 2FA for enhanced security

---

## 📝 Notes

- **Finance Role**: This role has been removed from the system
- **Demo Data**: These accounts are pre-seeded with demo data for testing
- **Multi-Tenancy**: Users are associated with specific dojos (tenant-based access)

---

## 🛠️ Troubleshooting

### Cannot Login?
1. Verify email and password are correct
2. Check if user account is active in database
3. Clear browser cache and cookies
4. Check application logs for errors

### Forgot Password?
Contact system administrator or use password reset functionality (if implemented).

---

## 📞 Support

For technical support or questions, contact:
- **System Admin**: admin@dojo.com
- **Documentation**: Check `/docs` folder
- **Issue Tracker**: Report issues via project management tool

---

**Last Updated**: January 19, 2026
**Version**: 1.0.0

