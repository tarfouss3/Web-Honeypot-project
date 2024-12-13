# Project Documentation

## Overview of Honeypots with Solution

This project includes multiple honeypot mechanisms to detect unauthorized access attempts. Here is a brief overview of each honeypot:

### `public/admin/customers.php`
**honeypot to expose unauthorized access attempts to the customer data (Broken Access Control). The honeypot includes the following features:**

1. **Session Check**: The script checks if the user is logged in by verifying the `user_id` in the session.
2. **Admin Check**: It queries the database to check if the logged-in user has an admin role.
3. **Header Check**: The script checks for a custom header `X-Is-Admin`. If this header is set to `true`, the user is granted access.
4. **Unauthorized Access Logging**: If the user is not an admin or the header is not set correctly, the access attempt is logged as a warning.
5. **Fake Data Display**: If unauthorized access is detected, fake customer data is displayed to mislead the attacker.

### `public/add_comment.php`

1. **Session Check**: The script checks if the user is logged in.
2. **XSS Detection**: The script checks for `<script>` tags in the comment content. If detected, it logs the attempt as a warning.
3. **Unauthorized Access Logging**: If XSS is detected, the access attempt is logged with details of the attacker and payload.

### `public/user/settings.php`

1. **Session Check**: The script checks if the user is logged in.
2. **Role Check**: It checks the user's role and prevents SuperUsers from changing their username.
3. **Header Check**: The script checks for a custom header `X-User-Id` to prevent broken access control attempts.
4. **Unauthorized Access Logging**: If an unauthorized access attempt is detected, it is logged with details of the attacker and target.

### `public/user/profile.php`

1. **Session Check**: The script checks if the user is logged in.
2. **SQL Injection Detection**: The script logs any attempt to access another user's profile via SQL injection.
3. **Unauthorized Access Logging**: If an unauthorized access attempt is detected, it is logged with details of the attacker and payload.

### `Th1s_1s_s3cur3_place.php`

1. **Unauthorized Access Logging**: The script logs any access to the hidden directory.
2. **Secret Key Check**: The script checks for a secret key in the query string. If the key is correct, it displays a welcome message; otherwise, it displays an error message.

## Overview of Secure Part of Web Environment

The secure part of the web environment includes several measures to ensure the safety and integrity of the application:

1. **Session Management**: User sessions are managed securely to prevent unauthorized access.
2. **Role-Based Access Control**: The application uses role-based access control to restrict access to certain parts of the application based on user roles.
3. **Input Validation**: All user inputs are validated and sanitized to prevent SQL injection and other common attacks.
4. **Logging**: All access attempts, especially unauthorized ones, are logged for monitoring and analysis.
5. **Custom Headers**: Custom headers are used to add an extra layer of security by verifying the presence and value of specific headers.
6. **Error Handling**: The application handles errors gracefully and does not reveal sensitive information to attackers.
7. **Fake Data**: Fake data is displayed to attackers to mislead them and prevent them from gaining useful information.
8. **XSS Protection**: The application checks for XSS payloads in user inputs and logs any attempts to inject malicious scripts.
9. **SQL Injection Detection**: The application detects and logs any attempts to perform SQL injection attacks
10. **Secret Key**: A secret key is used to protect a hidden directory from unauthorized access.
11. **Directory Listing**: Directory listing is disabled to prevent attackers from enumerating files and directories.
12. **Secure Configuration**: The application is configured securely to prevent common security misconfigurations.
13. **HTTPS**: The application uses HTTPS to encrypt data in transit
14. **Password Hashing**: User passwords are hashed securely to protect them from unauthorized access.
15. **Cross-Site Request Forgery (CSRF) Protection**: The application uses CSRF tokens to prevent CSRF attacks.
16. **Security Headers**: The application uses security headers to protect against common web vulnerabilities.
17. **Database Encryption**: Sensitive data in the database is encrypted to protect it from unauthorized access.
