<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Accessibility — {{ setting('app_name', 'Soil Lab') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/css/bootstrap.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #222; }
    </style>
</head>
<body>
    <div class="container py-5">
        <a href="{{ route('home') }}" class="btn btn-outline-success mb-4">&larr; Back to Home</a>
        <div class="card shadow-sm">
            <div class="card-body p-5">
                <h1 class="mb-4" style="color:#0a3d22;">Accessibility Statement</h1>
                <p>{{ setting('app_name', 'Soil Lab') }} is committed to ensuring digital accessibility for all users, including those with disabilities. We are continuously improving the user experience and applying relevant accessibility standards.</p>

                <h5 class="mt-4" style="color:#018840;">Measures to Support Accessibility</h5>
                <ul>
                    <li>Skip to main content link at the top of every page</li>
                    <li>Font size adjustment controls in the header</li>
                    <li>Screen reader compatible markup</li>
                    <li>Keyboard navigable menus</li>
                    <li>High contrast color schemes</li>
                </ul>

                <h5 class="mt-4" style="color:#018840;">Conformance Status</h5>
                <p>The Web Content Accessibility Guidelines (WCAG) define requirements for designers and developers to improve accessibility. We aim to conform to WCAG 2.1 Level AA.</p>

                <h5 class="mt-4" style="color:#018840;">Feedback</h5>
                <p>We welcome your feedback on the accessibility of this portal. Please contact us at:</p>
                <p><i class="fas fa-envelope mr-2" style="color:#018840;"></i> {{ setting('front.cho_email', 'cho.pulwama@jk.gov.in') }}</p>
                <p><i class="fas fa-phone mr-2" style="color:#018840;"></i> {{ setting('front.cho_phone') ?: '+91 1933 260234' }}</p>
            </div>
        </div>
    </div>
</body>
</html>
