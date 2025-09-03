<?
use ZealPHP\App;
?>
<!DOCTYPE html>
<html lang="en">
<?
App::render('/landing/common/__head', [
    'title' => $title,
    'description' => $description,
]);
App::render('/landing/common/__header', ['button' => $button]);
?>

<body>

    <!-- Contact Form Section -->
    <section class="contact-form-section">
        <div class="contact-form-container">
            <?php if ($success_message): ?>
                <div class="alert alert-success" role="alert">
                    <strong>Success!</strong> <?= htmlspecialchars($success_message) ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-error" role="alert">
                    <strong>Please fix the following errors:</strong>
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form class="contact-form" action="/contact" method="post">
                <div class="form-row">
                    <input type="hidden" name="csrf_token"
                        value="<?= htmlspecialchars($_SESSION['csrf_token'] ?? '') ?>">
                    <div class="form-group">
                        <label for="firstName">First Name *</label>
                        <input type="text" class="form-input <?= isset($errors['firstName']) ? 'error' : '' ?>"
                            id="firstName" name="firstName" required>
                        <?php if (isset($errors['firstName'])): ?>
                            <span id="firstName-error" class="error-message"
                                role="alert"><?= htmlspecialchars($errors['firstName']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="lastName">Last Name *</label>
                        <input type="text" class="form-input <?= isset($errors['lastName']) ? 'error' : '' ?>"
                            id="lastName" name="lastName" required>
                        <?php if (isset($errors['lastName'])): ?>
                            <span id="lastName-error" class="error-message"
                                role="alert"><?= htmlspecialchars($errors['lastName']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="email">Email Address *</label>
                        <input type="email" class="form-input <?= isset($errors['email']) ? 'error' : '' ?>" id="email"
                            name="email" required>
                        <?php if (isset($errors['email'])): ?>
                            <span id="email-error" class="error-message"
                                role="alert"><?= htmlspecialchars($errors['email']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" class="form-input <?= isset($errors['phone']) ? 'error' : '' ?>" id="phone"
                            name="phone">
                        <?php if (isset($errors['phone'])): ?>
                            <span id="phone-error" class="error-message"
                                role="alert"><?= htmlspecialchars($errors['phone']) ?></span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-group">
                    <label for="course">Course Interest</label>
                    <select id="course" name="course">
                        <option value="">Select a course</option>
                        <option value="beginner">Beginner Cyber Security</option>
                        <option value="advanced">Advanced Penetration Testing</option>
                        <option value="ethical-hacking">Ethical Hacking Masterclass</option>
                        <option value="network-security">Network Security Specialist</option>
                        <option value="custom">Custom Training Program</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="experience">Current Experience Level</label>
                    <select id="experience" name="experience">
                        <option value="">Select your level</option>
                        <option value="beginner">Complete Beginner</option>
                        <option value="some-knowledge">Some Basic Knowledge</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                        <option value="professional">Working Professional</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="message">Message *</label>
                    <textarea id="message" name="message"
                        placeholder="Tell us about your goals, questions, or how we can help you..."
                        required></textarea>
                </div>

                <button type="submit" class="submit-btn">Send Message</button>
            </form>
        </div>
    </section>
</body>

</html>