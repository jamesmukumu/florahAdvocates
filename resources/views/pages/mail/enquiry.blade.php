<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1" name="viewport">
  <meta name="x-apple-disable-message-reformatting">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="telephone=no" name="format-detection">
  <title>Enquiry Received | Florah Advocates</title>
  <style type="text/css">
    body { width:100%; height:100%; margin:0; padding:0; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; background-color:#f3f4f6; }
    p, h1, h2 { margin:0; padding:0; }
    .email-wrap { width:100%; max-width:640px; margin:0 auto; }
    .card { background-color:#ffffff; }
    .heading { color:#0f172a; font-size:26px; line-height:1.35; font-weight:700; font-family:Arial, Helvetica, sans-serif; }
    .text { color:#334155; font-size:15px; line-height:1.8; font-family:Arial, Helvetica, sans-serif; }
    .label { color:#0f172a; font-weight:700; }
    .section-title { color:#0f172a; font-size:18px; font-weight:700; font-family:Arial, Helvetica, sans-serif; }
    @media only screen and (max-width:600px) {
      .container-pad { padding:22px 18px !important; }
      .heading { font-size:22px !important; }
      .text { font-size:15px !important; }
    }
  </style>
</head>
<body>
  <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f3f4f6; padding:18px 10px;">
    <tr>
      <td align="center">
        <table class="email-wrap" width="100%" cellpadding="0" cellspacing="0" border="0">
          <tr>
            <td style="background-color:white; padding:24px 16px; text-align:center;">
              <img src="https://florah-client-cyan.vercel.app/images/logo-florah-advocates-removebg-preview.png" alt="Florah Advocates" style="display:block; margin:0 auto 10px auto; width:80px; max-width:40%; height:auto;">
         
            </td>
          </tr>

          <tr>
            <td class="card container-pad" style="padding:30px 32px;">
              <h1 class="heading">Your enquiry has been received</h1>

              <p class="text" style="margin-top:20px;">Dear {{ $emailBody['firstName'] }},</p>

              <p class="text" style="margin-top:12px;">
                Thank you for contacting Florah Advocates. We appreciate the trust you have placed in our firm.
                Your message has been received and shared with our legal team for review.
              </p>

              <p class="text" style="margin-top:12px;">
                You can expect an initial response within one business day on the next practical legal steps,
                relevant documentation, and the preferred consultation schedule.
              </p>

              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:24px; border-collapse:collapse;">
                <tr>
                  <td colspan="2" style="background-color:#eff6ff; color:#0f172a; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:700; padding:12px 14px; border:1px solid #cbd5e1;">
                    Enquiry Summary
                  </td>
                </tr>
                <tr>
                  <td style="width:30%; padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0f172a; font-weight:700;">Name</td>
                  <td style="width:70%; padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#334155;">{{ $emailBody['firstName'] }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0f172a; font-weight:700;">Email</td>
                  <td style="padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#334155;">{{ $emailBody['email'] }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0f172a; font-weight:700;">Phone</td>
                  <td style="padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#334155;">{{ $emailBody['phoneNumber'] }}</td>
                </tr>
                <tr>
                  <td style="padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#0f172a; font-weight:700; vertical-align:top;">Message</td>
                  <td style="padding:10px 12px; border:1px solid #cbd5e1; font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#334155;">{{ $emailBody['message'] }}</td>
                </tr>
              </table>

              <p class="text" style="margin-top:18px;">
                If you would like to share additional details before our team responds,
                please reply to this email and we will include them in our review.
              </p>

              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-top:22px; border:1px solid #e2e8f0; border-radius:8px;">
                <tr>
                  <td style="padding:16px 14px; font-family:Arial, Helvetica, sans-serif;">
                    <p class="section-title">Contact Us</p>
                    <p class="text" style="margin-top:8px;"><span class="label">Phone:</span> +254 714 416 325</p>
                    <p class="text" style="margin-top:6px;"><span class="label">Email:</span> litigation@floraadvocates.co.ke</p>
                    <p class="text" style="margin-top:6px;"><span class="label">Office:</span> General Conference Building, 3rd Riverside Drive, 2nd Floor, Kenya</p>
                  </td>
                </tr>
              </table>

              <p class="text" style="margin-top:20px;">Kind regards,</p>
              <p class="text" style="margin-top:2px; font-weight:700; color:#0f172a;">Florah Advocates Team</p>
            </td>
          </tr>

          <tr>
            <td style="background-color:white; padding:16px; text-align:center; font-family:Arial, Helvetica, sans-serif; font-size:13px; color:#cbd5e1;">
              <img src="https://florah-client-cyan.vercel.app/images/logo-florah-advocates-removebg-preview.png" alt="Florah Advocates" style="display:block; margin:0 auto 10px auto; width:80px; max-width:40%; height:auto;">
              &copy; {{ date('Y') }} Florah Advocates. All rights reserved.
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>
</html>
