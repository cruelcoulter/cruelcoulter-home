<footer class="footer">
  <div class="container">
    <span class="text-muted">
      <ul class="bd-footer-links">
<?php if (ENVIRON == "DEV") {echo "<li>" . basename($_SERVER['PHP_SELF']) . "</li>";} ?>
<li><a href="<?php echo SITE_URL_ROOT; ?>">Home</a></li>
<li><a href="<?php echo SITE_URL_ROOT . "family/"; ?>">Family</a></li>
<li><a href="<?php echo SITE_URL_ROOT . "civilwar/"; ?>">Civil War</a></li>
<li><a href="<?php echo SITE_URL_ROOT . "blog/"; ?>">Blog</a></li>
<li><a href="<?php echo SITE_URL_ROOT . "blog/feedback/"; ?>">Feedback</a></li>
<li><a target="_blank" href="https://www.goodreads.com/user/show/3452002-ron-coulter"><i class="fab fa-goodreads fa-lg"></a></i></li>
<li><a target="_blank" href="https://github.com/cruelcoulter"><i class="fab fa-github-square fa-lg"></i></a></li>
<li><a target="_blank" href="https://twitter.com/cruelcoulter"><i class="fab fa-twitter-square fa-lg"></i></a></li>
</ul>

    </span>
  </div>
</footer>
