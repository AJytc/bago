<p>Hello {{ $user->name }},</p>

<p>Your account has been created. You can now log in using the following credentials:</p>

<ul>
    <li><strong>Email:</strong> {{ $user->email }}</li>
    <li><strong>Password:</strong> {{ $plainPassword }}</li>
</ul>

<p>📧 Please verify your email before logging in.</p>
<p>🔒 We recommend changing your password after your first login.</p>

<p>Thank you!</p>
