{{--
    resources/views/emails/otp.blade.php

    Variables:
      $name     string  Recipient first name, e.g. "Jewel"
      $code     string  The one-time code, e.g. "314395"
      $minutes  int     Minutes until the code expires (use your real value)

    Notes:
      - Table layout + inline styles only, so it renders in Gmail, Outlook and Apple Mail.
      - The logo must be a PNG on a PUBLIC url (email clients block SVG and localhost).
        Export logo-mark.svg to a 112x112 PNG (shown at 28x28 for sharp retina), save it as public/images/email-logo.png,
        and make sure APP_URL points at your real domain in production.
      - The bubble strip is plain table cells with rounded borders. Outlook desktop
        ignores border-radius and shows small squares instead; everything still reads fine.
--}}
@php
    $app = config('app.name', 'PhilNITS Prep');
    $font = "Inter, -apple-system, 'Segoe UI', Helvetica, Arial, sans-serif";
    $mono = "'SFMono-Regular', Menlo, Consolas, 'Courier New', monospace";
    $filled = [2, 5, 8, 10]; // which of the 12 bubbles are filled in
@endphp
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light">
    <meta name="supported-color-schemes" content="light">
    <title>Your verification code</title>
</head>
<body style="margin:0; padding:0; background-color:#F6F4EF; -webkit-text-size-adjust:100%;">

    {{-- Hidden preheader: the preview text shown next to the subject in the inbox --}}
    <div style="display:none; max-height:0; overflow:hidden; opacity:0; color:#F6F4EF; font-size:1px; line-height:1px;">
        Enter this code to verify your email. It expires in {{ $minutes }} minutes.
    </div>

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#F6F4EF" style="background-color:#F6F4EF;">
        <tr>
            <td align="center" style="padding:32px 16px;">

                <table role="presentation" width="480" cellpadding="0" cellspacing="0" border="0" style="width:100%; max-width:480px;">

                    {{-- Header --}}
                    <tr>
                        <td style="padding:0 4px 16px 4px;">
                            <table role="presentation" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="vertical-align:middle; padding-right:8px;">
                                        <img src="{{ asset('images/email-logo.png') }}" width="28" height="28" alt="" style="display:block; border:0; outline:none;">
                                    </td>
                                    <td style="vertical-align:middle; font-family:{!! $font !!}; font-size:15px; font-weight:500; color:#1F3A5F;">
                                        {{ $app }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{-- Card --}}
                    <tr>
                        <td bgcolor="#FFFFFF" style="background-color:#FFFFFF; border:1px solid #D9D5C9; border-radius:12px; overflow:hidden;">

                            {{-- Answer-sheet bubble strip --}}
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#FBFAF7" style="background-color:#FBFAF7; border-bottom:1px solid #E3DFD5; border-radius:12px 12px 0 0;">
                                <tr>
                                    <td style="padding:16px 28px;">
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="table-layout:fixed;">
                                            <tr>
                                                @foreach (range(0, 11) as $i)
                                                    <td align="center" style="font-size:0; line-height:0;">
                                                        @if (in_array($i, $filled))
                                                            <div style="width:10px; height:10px; margin:0 auto; border-radius:50%; background-color:#1F3A5F; border:1px solid #1F3A5F; font-size:0; line-height:0;">&nbsp;</div>
                                                        @else
                                                            <div style="width:10px; height:10px; margin:0 auto; border-radius:50%; border:1px solid #CFCABD; font-size:0; line-height:0;">&nbsp;</div>
                                                        @endif
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="padding:28px 28px 26px 28px;">

                                        <p style="margin:0 0 10px 0; font-family:{!! $mono !!}; font-size:12px; line-height:16px; color:#6E6A60;">
                                            Email verification
                                        </p>
                                        <p style="margin:0 0 8px 0; font-family:{!! $font !!}; font-size:26px; line-height:30px; font-weight:500; letter-spacing:-0.5px; color:#1B1A17;">
                                            Your code is ready, {{ $name }}.
                                        </p>
                                        <p style="margin:0 0 22px 0; font-family:{!! $font !!}; font-size:14px; line-height:22px; color:#6E6A60;">
                                            Enter it in {{ $app }} to verify your email and finish creating your account.
                                        </p>

                                        {{-- The code --}}
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" bgcolor="#F6F4EF" style="background-color:#F6F4EF; border:1px solid #D9D5C9; border-radius:10px; padding:20px 12px;">
                                                    <span style="font-family:{!! $mono !!}; font-size:36px; line-height:36px; font-weight:bold; letter-spacing:10px; padding-left:10px; color:#1F3A5F;">{{ $code }}</span>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin:10px 0 24px 0; text-align:center; font-family:{!! $font !!}; font-size:13px; line-height:20px; color:#6E6A60;">
                                            Expires in <strong style="font-weight:500; color:#1B1A17;">{{ $minutes }} minutes</strong>. Use it once.
                                        </p>

                                        {{-- Mini multiple-choice question --}}
                                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td style="border-top:1px solid #E3DFD5; padding-top:18px;">

                                                    <p style="margin:0 0 12px 0; font-family:{!! $font !!}; font-size:14px; line-height:20px; font-weight:500; color:#1B1A17;">
                                                        <span style="font-family:{!! $mono !!}; font-size:12px; font-weight:normal; color:#6E6A60; padding-right:8px;">Q1</span>Did you just sign up for {{ $app }}?
                                                    </p>

                                                    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td width="32" valign="top" style="padding-bottom:8px;">
                                                                <div style="width:22px; height:22px; border-radius:50%; background-color:#1F3A5F; color:#F6F4EF; font-family:{!! $mono !!}; font-size:12px; line-height:22px; text-align:center;">A</div>
                                                            </td>
                                                            <td valign="top" style="padding-bottom:8px; font-family:{!! $font !!}; font-size:13px; line-height:22px; color:#6E6A60;">
                                                                <strong style="font-weight:500; color:#1B1A17;">Yes.</strong> Enter the code above in the app.
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td width="32" valign="top">
                                                                <div style="width:20px; height:20px; border-radius:50%; border:1px solid #CFCABD; color:#6E6A60; font-family:{!! $mono !!}; font-size:12px; line-height:20px; text-align:center;">B</div>
                                                            </td>
                                                            <td valign="top" style="font-family:{!! $font !!}; font-size:13px; line-height:22px; color:#6E6A60;">
                                                                <strong style="font-weight:500; color:#1B1A17;">No.</strong> Ignore this email. Nothing happens unless the code is used.
                                                            </td>
                                                        </tr>
                                                    </table>

                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:16px 4px 0 4px; font-family:{!! $font !!}; font-size:12px; line-height:19px; color:#6E6A60;">
                            <p style="margin:0 0 4px 0;">Keep this code private. We will never ask you for it.</p>
                            <p style="margin:0;">{{ $app }} is an independent study tool, not affiliated with PhilNITS or ITPEC.</p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>
