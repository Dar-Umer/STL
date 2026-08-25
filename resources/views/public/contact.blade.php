<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Contact Us — {{ setting('app_name', 'Soil Lab') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #222; }
        .contact-header { background:linear-gradient(135deg,#0a4b2a 0%,#018840 100%);padding:16px 0;border-bottom:3px solid #f3b229; }
        .contact-header h1{color:#fff;font-size:1rem;font-weight:800;margin:0;}
        .contact-header a{color:rgba(255,255,255,.7);font-size:.8rem;text-decoration:none;}
        .contact-header a:hover{color:#fff;}
        .contact-card{background:#fff;border-radius:12px;border:1px solid #e2e6ea;padding:24px;height:100%;transition:box-shadow .2s;}
        .contact-card:hover{box-shadow:0 4px 16px rgba(1,136,64,.08);}
        .contact-card .icon{width:48px;height:48px;border-radius:12px;display:flex;align-items:center;justify-content:center;font-size:1.2rem;color:#fff;margin-bottom:12px;}
    </style>
</head>
<body>
    <div class="contact-header">
        <div class="container d-flex justify-content-between align-items-center">
            <h1><i class="fas fa-phone-alt mr-2"></i> Contact Us</h1>
            <a href="{{ route('home') }}"><i class="fas fa-arrow-left mr-1"></i> Back to Home</a>
        </div>
    </div>

    <div class="container py-5">
        <div class="row mb-4">
            <div class="col text-center">
                <h2 style="color:#0a3d22;font-weight:800;">Get in Touch</h2>
                <p class="text-muted" style="max-width:500px;margin:0 auto;">Have questions or need assistance? Reach out to us through any of the channels below.</p>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-4 mb-3">
                <div class="contact-card">
                    <div class="icon" style="background:linear-gradient(135deg,#018840,#0a5a2a);"><i class="fas fa-map-marker-alt"></i></div>
                    <h5 style="color:#0a3d22;font-weight:700;">Address</h5>
                    <p class="text-muted mb-0" style="font-size:.88rem;">{{ setting('front.cho_address', 'Mini Secretariat, Pulwama, J&K 192301') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="contact-card">
                    <div class="icon" style="background:linear-gradient(135deg,#f3b229,#d4941a);"><i class="fas fa-phone-alt"></i></div>
                    <h5 style="color:#0a3d22;font-weight:700;">Phone</h5>
                    <p class="text-muted mb-0" style="font-size:.88rem;">{{ setting('front.cho_phone', '+91-XXXX-XXXXXX') }}</p>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="contact-card">
                    <div class="icon" style="background:linear-gradient(135deg,#0a4b2a,#072b17);"><i class="fas fa-envelope"></i></div>
                    <h5 style="color:#0a3d22;font-weight:700;">Email</h5>
                    <p class="text-muted mb-0" style="font-size:.88rem;">{{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}</p>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-5">
                <div class="row">
                    <div class="col-md-6 mb-4 mb-md-0">
                        <h4 style="color:#0a3d22;font-weight:700;">Office Hours</h4>
                        <table class="table table-borderless" style="font-size:.88rem;">
                            <tr><td class="pl-0" style="font-weight:600;">Monday – Friday</td><td class="pr-0 text-right">9:00 AM – 5:00 PM</td></tr>
                            <tr><td class="pl-0" style="font-weight:600;">Saturday</td><td class="pr-0 text-right">9:00 AM – 1:00 PM</td></tr>
                            <tr><td class="pl-0" style="font-weight:600;">Sunday</td><td class="pr-0 text-right text-danger">Closed</td></tr>
                        </table>
                        <p class="text-muted mt-3" style="font-size:.82rem;"><i class="fas fa-info-circle mr-1" style="color:#018840;"></i> Field staff may have flexible schedules. Please call ahead to confirm availability.</p>
                    </div>
                    <div class="col-md-6">
                        <h4 style="color:#0a3d22;font-weight:700;">Quick Links</h4>
                        <ul class="list-unstyled" style="font-size:.88rem;">
                            <li class="mb-2"><a href="{{ route('public.track') }}" style="color:#018840;text-decoration:none;"><i class="fas fa-search mr-2"></i> Track Your Report</a></li>
                            <li class="mb-2"><a href="{{ route('public.sample-request') }}" style="color:#018840;text-decoration:none;"><i class="fas fa-clipboard-list mr-2"></i> Request Sample Collection</a></li>
                            <li class="mb-2"><a href="{{ route('accessibility') }}" style="color:#018840;text-decoration:none;"><i class="fas fa-universal-access mr-2"></i> Accessibility</a></li>
                            <li class="mb-2"><a href="{{ route('home') }}" style="color:#018840;text-decoration:none;"><i class="fas fa-home mr-2"></i> Home</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-success rounded-pill px-4">&larr; Back to Home</a>
        </div>
    </div>

    <footer style="background:#072b17;padding:20px 0;color:rgba(255,255,255,.55);font-size:.75rem;text-align:center;">
        <div class="container">
            &copy; {{ date('Y') }} {{ setting('app_name', 'Soil Lab') }} &mdash; Government of Jammu &amp; Kashmir (UT)
        </div>
    </footer>
</body>
</html>