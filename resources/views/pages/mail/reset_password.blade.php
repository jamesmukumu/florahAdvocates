@php
  $recipientName = 'there';
  $resetLink =  $resetLink ?? "";
@endphp

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="telephone=no" name="format-detection">
  <title>Reset Your Password | Flora Advocates</title>
  <style type="text/css">
    body { width:100%; height:100%; margin:0; padding:0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color:#f3f4f6; }
    p, h1 { margin:0; padding:0; }
    .email-wrap { width:100%; max-width:640px; margin:0 auto; }
    .card { background-color:#ffffff; }
    .heading { color:#0f172a; font-size:26px; line-height:1.35; font-weight:700; font-family:Arial, Helvetica, sans-serif; }
    .text { color:#334155; font-size:15px; line-height:1.8; font-family:Arial, Helvetica, sans-serif; }
    .btn { display:inline-block; padding:13px 22px; background-color:#0f172a; color:#ffffff !important; font-weight:700; font-size:14px; font-family:Arial, Helvetica, sans-serif; text-decoration:none; border-radius:6px; }
    .alert-warning { border:1px solid #f59e0b; background-color:#fffbeb; color:#92400e; font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:1.7; padding:12px 14px; border-radius:8px; }
    .alert-error { border:1px solid #fca5a5; background-color:#fef2f2; color:#991b1b; font-family:Arial, Helvetica, sans-serif; font-size:14px; line-height:1.7; padding:12px 14px; border-radius:8px; }
    @media only screen and (max-width:600px) {
      .container-pad { padding:22px 18px !important; }
      .heading { font-size:22px !important; }
      .text { font-size:15px !important; }
      .btn-wrap { text-align:left !important; }
    }
  </style>
</head>
<body>
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f4f6; padding:18px 10px;">
    <tr>
      <td align="center">
        <table class="email-wrap" width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="background-color:#ffffff; padding:24px 16px; text-align:center;">
              <img src="https://florah-client-cyan.vercel.app/images/logo-florah-advocates-removebg-preview.png" alt="Florah Advocates" style="display:block; margin:0 auto; width:80px; max-width:40%; height:auto;">
            </td>
          </tr>

          <tr>
            <td class="card container-pad" style="padding:30px 32px;">
              <h1 class="heading">Reset your password</h1>

              <p class="text" style="margin-top:20px;">Hello {{ $recipientName }},</p>

              <p class="text" style="margin-top:12px;">
                We received a request to reset your Florah Advocates account password.
                Click the button below to continue.
              </p>

              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:18px;">
                <tr>
                  <td class="alert-warning">
                    <strong>Important:</strong> This reset link expires in <strong>10 minutes</strong> for your account security.
                  </td>
                </tr>
              </table>

              @if($resetLink)
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:22px;">
                  <tr>
                    <td class="btn-wrap" align="left">
                      <a href="{{ $resetLink }}" class="btn" target="_blank" rel="noopener noreferrer">Reset Password</a>
                    </td>
                  </tr>
                </table>

                <p class="text" style="margin-top:14px; font-size:13px; line-height:1.6; color:#64748b;">
                  If the button does not work, copy and paste this link into your browser:<br>
                  <a href="{{ $resetLink }}" target="_blank" rel="noopener noreferrer" style="color:#0f172a;">{{ $resetLink }}</a>
                </p>
              @else
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:22px;">
                  <tr>
                    <td class="alert-error">
                      <strong>Link unavailable:</strong> We could not generate your reset link. Please request a new password reset email.
                    </td>
                  </tr>
                </table>
              @endif

              <p class="text" style="margin-top:20px;">
                If you did not request this password reset, you can safely ignore this email.
              </p>

              <p class="text" style="margin-top:20px;">Regards,</p>
              <p class="text" style="margin-top:2px; font-weight:700; color:#0f172a;">Flora C & Advocates Team</p>
            </td>
          </tr>

          <tr>
            <td style="background-color:#ffffff; padding:16px; text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#94a3b8;">
              <img src="https://florah-client-cyan.vercel.app/images/logo-florah-advocates-removebg-preview.png" alt="Florah Advocates" style="display:block; margin:0 auto 10px auto; width:80px; max-width:40%; height:auto;">
              &copy; {{ date('Y') }} Flora C & Company Advocates. All rights reserved.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
