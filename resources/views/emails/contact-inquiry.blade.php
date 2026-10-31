<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>New Contact Inquiry - EasyTicket</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f8fafc;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
      color: #1e293b;
      -webkit-font-smoothing: antialiased;
    }
    .wrapper {
      width: 100%;
      background-color: #f8fafc;
      padding: 40px 15px;
    }
    .container {
      max-width: 600px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 18px;
      overflow: hidden;
      border: 1px solid #e2e8f0;
      box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .header {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      padding: 30px;
      text-align: center;
      color: #ffffff;
    }
    .badge {
      display: inline-block;
      padding: 4px 12px;
      font-size: 11px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      border-radius: 50px;
      background: rgba(239, 68, 68, 0.2);
      color: #fca5a5;
      border: 1px solid rgba(239, 68, 68, 0.3);
      margin-bottom: 12px;
    }
    .title {
      margin: 0;
      font-size: 22px;
      font-weight: 900;
      letter-spacing: -0.02em;
      color: #ffffff;
    }
    .content {
      padding: 30px;
    }
    .lead-box {
      background: #f1f5f9;
      border-radius: 14px;
      padding: 20px;
      margin-bottom: 24px;
      border: 1px solid #e2e8f0;
    }
    .row {
      display: flex;
      margin-bottom: 12px;
      font-size: 13px;
    }
    .row:last-child {
      margin-bottom: 0;
    }
    .label {
      width: 130px;
      font-weight: 700;
      color: #64748b;
      text-transform: uppercase;
      font-size: 11px;
      letter-spacing: 0.03em;
    }
    .value {
      font-weight: 600;
      color: #0f172a;
      flex: 1;
    }
    .value a {
      color: #dc2626;
      text-decoration: none;
      font-weight: 700;
    }
    .value a:hover {
      text-decoration: underline;
    }
    .message-section {
      margin-bottom: 25px;
    }
    .message-title {
      font-size: 12px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: #475569;
      margin-bottom: 8px;
    }
    .message-body {
      background: #ffffff;
      border: 1px solid #cbd5e1;
      border-left: 4px solid #dc2626;
      border-radius: 10px;
      padding: 16px 20px;
      font-size: 14px;
      line-height: 1.6;
      color: #334155;
      white-space: pre-wrap;
    }
    .cta-container {
      text-align: center;
      padding: 10px 0 20px;
    }
    .btn {
      display: inline-block;
      padding: 12px 24px;
      font-size: 13px;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.04em;
      text-decoration: none;
      border-radius: 10px;
      margin: 6px;
    }
    .btn-primary {
      background-color: #dc2626;
      color: #ffffff !important;
    }
    .btn-secondary {
      background-color: #10b981;
      color: #ffffff !important;
    }
    .footer {
      background: #f8fafc;
      padding: 20px;
      text-align: center;
      font-size: 11px;
      color: #94a3b8;
      border-top: 1px solid #e2e8f0;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    <div class="container">
      <!-- Header -->
      <div class="header">
        <div class="badge">Website Lead Notification</div>
        <h1 class="title">New Inquiry Received</h1>
      </div>

      <!-- Main Content -->
      <div class="content">
        <!-- Lead Information Box -->
        <div class="lead-box">
          <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
            <tr>
              <td style="padding: 6px 0; width: 130px; font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase;">Sender Name:</td>
              <td style="padding: 6px 0; font-weight: 800; color: #0f172a;">{{ $inquiry->name }}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; width: 130px; font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase;">Email Address:</td>
              <td style="padding: 6px 0; font-weight: 700; color: #dc2626;">
                <a href="mailto:{{ $inquiry->email }}" style="color: #dc2626; text-decoration: none;">{{ $inquiry->email }}</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 6px 0; width: 130px; font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase;">Phone / WhatsApp:</td>
              <td style="padding: 6px 0; font-weight: 800; color: #0f172a; font-family: monospace;">
                <a href="tel:{{ $inquiry->phone }}" style="color: #0f172a; text-decoration: none;">{{ $inquiry->phone }}</a>
              </td>
            </tr>
            <tr>
              <td style="padding: 6px 0; width: 130px; font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase;">Subject:</td>
              <td style="padding: 6px 0; font-weight: 700; color: #0f172a;">{{ $inquiry->subject ?: 'General Inquiry' }}</td>
            </tr>
            <tr>
              <td style="padding: 6px 0; width: 130px; font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase;">Received At:</td>
              <td style="padding: 6px 0; color: #64748b; font-size: 12px;">{{ $inquiry->created_at ? $inquiry->created_at->format('M d, Y - h:i A') : date('M d, Y - h:i A') }}</td>
            </tr>
            @if($inquiry->ip_address)
            <tr>
              <td style="padding: 6px 0; width: 130px; font-weight: 700; color: #64748b; font-size: 11px; text-transform: uppercase;">IP Address:</td>
              <td style="padding: 6px 0; color: #94a3b8; font-size: 11px; font-family: monospace;">{{ $inquiry->ip_address }}</td>
            </tr>
            @endif
          </table>
        </div>

        <!-- Message Body -->
        <div class="message-section">
          <div class="message-title">Message Body</div>
          <div class="message-body">{{ $inquiry->message }}</div>
        </div>

        <!-- Action Buttons -->
        <div class="cta-container">
          <a href="mailto:{{ $inquiry->email }}?subject={{ rawurlencode('Re: ' . ($inquiry->subject ?: 'EasyTicket Inquiry')) }}" class="btn btn-primary">
            ✉️ Reply via Email
          </a>
          <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $inquiry->phone) }}?text={{ rawurlencode('Hello ' . $inquiry->name . ', thank you for contacting EasyTicket.') }}" class="btn btn-secondary" target="_blank">
            💬 Open WhatsApp Chat
          </a>
        </div>
      </div>

      <!-- Footer -->
      <div class="footer">
        EasyTicket Operations & Commercial Portal &bull; Automated Inquiry Notification<br>
        This inquiry was submitted from the public Contact Us form on EasyTicket.
      </div>
    </div>
  </div>
</body>
</html>
