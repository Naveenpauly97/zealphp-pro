<!-- Testimonials -->
<section class="testimonials-header container">
    <h2>What Our <span>500+</span> Learners Say...</h2>
    <p>See how our course has transformed careers across the globe</p>
</section>
<section class="testimonials container">
    <?
    foreach ($testimonials as $testimonial) {
        ?>
        <div class="testimonial-box">
            <div class="profile-pic">
                <img src="<?= $testimonial['image'] ?? '' ?>" alt="Profile" />
            </div>
            <div class="testimonial-content">
                <h3><?= $testimonial['name'] ?? '' ?></h3>
                <p>
                    <?= $testimonial['message'] ?? 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do
                eiusmod tempor incididunt ut labore et dolore magna aliqua.' ?>
                </p>
            </div>
        </div>
    <?
    }
    ?>
</section>
<section class="logos-section">
    <div class="logos">
        <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" alt="Google" />
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a2/DEL_PRI_CMYK_aw.jpg" alt="Deloitte" />
        <img src="https://globalagencyawards.net/wp-content/uploads/2023/09/TCS-Research-Logo.png" alt="TCS" />
        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" alt="Amazon" />
    </div>
</section>