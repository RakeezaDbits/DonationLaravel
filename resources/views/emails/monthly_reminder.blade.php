<!DOCTYPE html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>{{ $subject }}</title>
</head>

<body style="margin:0;padding:0;font-family:Arial,sans-serif;background-color:#f5f7fa;">
  <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" style="background-color:#f5f7fa;padding:20px 0;">
    <tr>
      <td align="center">
        <!-- Card container -->
        <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;border-radius:12px;border:1px solid #e0e0e0;box-shadow:0 2px 8px rgba(0,0,0,0.05);overflow:hidden;">
          <!-- Header -->
          <tr>
            <td align="center" style="padding:20px 0;position:relative;background-color:#3B82F6;color:#fff;">
              <img src="{{ asset('logo.jpeg') }}" alt="Logo" width="80" style="display:block;margin-bottom:10px;border-radius:8px;">
              <div style="font-size:24px;font-weight:bold;">MY HARF Donations</div>
            </td>
          </tr>

          <!-- Content -->
          <tr>
            <td style="padding:30px;text-align:center;color:#333;">
              <p>Dear {{ $name }},</p>
              <p>This is a friendly reminder that your monthly donation is due.</p>

              <!-- Highlight Box using table -->
              <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin:20px 0;">
                <tr>
                  <!-- Pledged Amount -->
                  <td width="33%" style="padding:10px;">
                    <table width="100%" cellpadding="10" cellspacing="0" border="0" style="background:#ffffff;border:1px solid #e0e0e0;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.05);text-align:center;">
                      <tr>
                        <td>
                          <!-- SVG Icon -->
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#3B82F6" viewBox="0 0 24 24">
                            <path d="M12 0C5.372 0 0 5.372 0 12c0 6.628 5.372 12 12 12 6.628 0 12-5.372 12-12C24 5.372 18.628 0 12 0zm0 2c5.523 0 10 4.477 10 10 0 5.523-4.477 10-10 10-5.523 0-10-4.477-10-10C2 6.477 6.477 2 12 2zm0 5a3 3 0 1 0 0 6 3 3 0 0 0 0-6z"/>
                          </svg>
                          <div style="font-size:16px;font-weight:bold;margin-top:8px;">Pledged Amount</div>
                          <div>{{ $monthly_amount }}</div>
                        </td>
                      </tr>
                    </table>
                  </td>

                  <!-- Next Due Date -->
                  <td width="33%" style="padding:10px;">
                    <table width="100%" cellpadding="10" cellspacing="0" border="0" style="background:#ffffff;border:1px solid #e0e0e0;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.05);text-align:center;">
                      <tr>
                        <td>
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#3B82F6" viewBox="0 0 24 24">
                            <path d="M7 10h5v5H7z"/><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 18H5V8h14v13z"/>
                          </svg>
                          <div style="font-size:16px;font-weight:bold;margin-top:8px;">Next Due Date</div>
                          <div>{{ $due_date }}</div>
                        </td>
                      </tr>
                    </table>
                  </td>

                  <!-- Last Donation -->
                  <td width="33%" style="padding:10px;">
                    <table width="100%" cellpadding="10" cellspacing="0" border="0" style="background:#ffffff;border:1px solid #e0e0e0;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.05);text-align:center;">
                      <tr>
                        <td>
                          <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#3B82F6" viewBox="0 0 24 24">
                            <path d="M12 8V12L15 15L16.5 13.5L14 11V8z"/><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2z"/>
                          </svg>
                          <div style="font-size:16px;font-weight:bold;margin-top:8px;">Last Donation</div>
                          <div>{{ $last_donation_date }}</div>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Button -->
              <div style="margin:20px 0;">
                <a href="https://harf.org/login" style="display:inline-block;padding:12px 25px;background:#3B82F6;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold;">Make Donation Now</a>
              </div>

              <!-- Impact Stats -->
              <p style="font-weight:bold;margin-top:30px;">Your Impact So Far:</p>
              <table width="100%" cellpadding="10" cellspacing="0" border="0" style="text-align:center;">
                <tr>
                  <td style="background:#f8fafc;border:1px solid #e0e0e0;border-radius:12px;padding:15px;">
                    <div style="font-size:24px;font-weight:bold;color:#3B82F6;">{{ $total_donated }}</div>
                    <div style="font-size:14px;color:#64748b;">Total Donated</div>
                  </td>
                  <td style="background:#f8fafc;border:1px solid #e0e0e0;border-radius:12px;padding:15px;">
                    <div style="font-size:24px;font-weight:bold;color:#3B82F6;">{{ $donation_count }}</div>
                    <div style="font-size:14px;color:#64748b;">Donations Made</div>
                  </td>
                  <td style="background:#f8fafc;border:1px solid #e0e0e0;border-radius:12px;padding:15px;">
                    <div style="font-size:24px;font-weight:bold;color:#3B82F6;">{{ $impact_stats }}</div>
                    <div style="font-size:14px;color:#64748b;">Lives Impacted</div>
                  </td>
                </tr>
              </table>

              <p style="margin-top:20px;">Thank you for your continued generosity and commitment!</p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f1f5f9;padding:20px;text-align:center;font-size:14px;color:#64748b;">
              HARF Organization | <a href="https://harf.org" style="color:#3B82F6;text-decoration:none;">harf.org</a> | info@harf.org | +1 (555) 123-4567
              <p style="margin-top:10px;font-size:12px;">&copy; 2023 HARF Organization. All rights reserved.</p>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>
</body>

</html>
