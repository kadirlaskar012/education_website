<div class="legal-card">
    <h1>Contact Our Editorial Team</h1>
    <p>Have an inquiry, feedback, or a correction regarding an educational update? Please reach out to our editorial desk using the form below or via direct email.</p>

    <div style="margin: 1.5rem 0; padding: 1rem; background-color: #f8fafc; border-left: 4px solid #1e3a8a;">
        <p><strong>Editorial Email:</strong> <a href="mailto:<?= htmlspecialchars($site_settings['contact_email'] ?? 'contact@edugovnews.in') ?>"><?= htmlspecialchars($site_settings['contact_email'] ?? 'contact@edugovnews.in') ?></a></p>
        <p><strong>Response Time:</strong> Typically within 24–48 business hours.</p>
    </div>

    <form class="contact-form" action="#" method="post" onsubmit="alert('Thank you! Your message has been received.'); return false;">
        <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" required placeholder="Enter your full name">
        </div>
        <div class="form-group">
            <label for="email">Your Email Address</label>
            <input type="email" id="email" name="email" required placeholder="name@example.com">
        </div>
        <div class="form-group">
            <label for="subject">Subject</label>
            <input type="text" id="subject" name="subject" required placeholder="Subject of your message">
        </div>
        <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required placeholder="Write your feedback or query here..."></textarea>
        </div>
        <button type="submit">Send Message</button>
    </form>
</div>
