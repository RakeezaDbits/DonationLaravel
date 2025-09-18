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
                        <td align="center" style="padding:20px 0;position:relative;background-color:#ef4444;color:#fff;">
                            <img src="https://harf.org/logo.jpeg" alt="Logo" width="80" style="display:block;margin-bottom:10px;border-radius:8px;">
                            <div style="font-size:24px;font-weight:bold;">MY HARF Donations</div>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:30px;text-align:center;color:#333;">
                            <p>Dear {{ $name }},</p>
                            <table width="100%" cellpadding="15" cellspacing="0" border="0" style="background:#ffffff;border:1px solid #e0e0e0;border-radius:12px;box-shadow:0 1px 4px rgba(0,0,0,0.05);margin:20px 0;">
                                <tr>
                                    <td style="text-align:center;">
                                        <!-- SVG Cross Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="#ef4444" viewBox="0 0 24 24" style="margin-bottom:10px;">
                                            <path d="M12 0C5.373 0 0 5.373 0 12s5.373 12 12 12 12-5.373 12-12S18.627 0 12 0zm5.293 16.707l-1.414 1.414L12 13.414l-3.879 3.879-1.414-1.414L10.586 12 6.707 8.121l1.414-1.414L12 10.586l3.879-3.879 1.414 1.414L13.414 12l3.879 3.879z"/>
                                        </svg>
                                        <div style="font-size:18px;font-weight:bold;margin-bottom:10px;">Donation Rejected</div>
                                        <p style="margin:0;">We regret to inform you that your recent donation of <strong>{{ $amount }}</strong> has been rejected.</p>

                                        @if(!empty($reason))
                                            <p style="margin:10px 0 0;"><strong>Reason:</strong> {{ $reason }}</p>
                                        @endif

                                        <p style="margin:10px 0 0;">If you have any questions, please contact <a href="mailto:support@harf.org" style="color:#3B82F6;text-decoration:none;">support@harf.org</a></p>
                                    </td>
                                </tr>
                            </table>

                            <div style="margin:20px 0;">
                                <a href="https://harf.org/login" style="display:inline-block;padding:12px 25px;background:#ef4444;color:#fff;text-decoration:none;border-radius:8px;font-weight:bold;">View Your Account</a>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background:#f1f5f9;padding:20px;text-align:center;font-size:14px;color:#64748b;">
                            HARF Organization | <a href="https://harf.org" style="color:#3B82F6;text-decoration:none;">harf.org</a>
                            <p style="margin-top:10px;font-size:12px;">&copy; {{ date('Y') }} HARF Organization. All rights reserved.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
