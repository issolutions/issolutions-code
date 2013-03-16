<?php

// Warning :
// > You should copy this file (e.g. my-static-slides.php) if you want to customize the static slides!
// > Automatic update will replace this file so all your changes can be lost :(

// --- Slide template -----------------------------------
//<div class="da-slide">
//	<h2>Slide tittle</h2>
//	<p>Slide text here...</p>
//	<a href="http://jltweb.info/realisations/wp-parallax-content-plugin/" class="da-link">Read more link</a>
//	<div class="da-img"><img src="$plugin_abs_path/images/1.png" alt="Slkide image" /></div>
//</div>
// -------------------------------------------------------

$outputStatic = <<<STATICOUTPUT
<div id="da-slider" class="da-slider">
	<div class="da-slide">
		<h2>Professional Services</h2>
		<p><b>What kind of position are you looking for?</b></br>IS Solutions provides the service you need to compete in today&#39;s high tech industry. Choosing IS Solutions to support your talent acquisition process is an important decision for any organization that requires Top Performers in Non&#45;technical positions.</p>
		<a href="../is-solutionsllc/services/professional-services/" class="da-link">Read more</a>
		<div class="da-img"><img src="../is-solutionsllc/wp-content/plugins/wp-parallax-content-slider/images/1.png" alt="Professional Services" /></div>
	</div>
	<div class="da-slide">
		<h2>Aerospace & Defense</h2>
		<p><b>Leading Provider of Talent Acquisition Process Solutions &#40;TAPS&#41;.</b></br>IS Solutions tailors staffing &#47; recruiting programs and large acquisition campaigns for major Aerospace and Defense companies worldwide.</p>
		<a href="../is-solutionsllc/services/aerospace-defense/" class="da-link">Read more</a>
		<div class="da-img"><img src="../is-solutionsllc/wp-content/plugins/wp-parallax-content-slider/images/aerospaceDefense.png" alt="Aerospace and Defense" /></div>
	</div>
	<div class="da-slide">
		<h2>Commercial IT</h2>
		<p><b>Excellence in Staffing Support</b></br>Specialized recruiters in our IT&#47;Telecom Recruiting Network know the unique skills and requirements needed in within the Technology community.</p>
		<a href="../is-solutionsllc/services/commercial-it/ " class="da-link">Read more</a>
		<div class="da-img"><img src="../is-solutionsllc/wp-content/plugins/wp-parallax-content-slider/images/commercialIT.png" alt="Commercial IT" /></div>
	</div>
	<div class="da-slide">
		<h2>Commercial Engineering</h2>
		<p><b>Specialized in delivering highly engineered staffing solutions.</b></br>Our Engineering specialty recruiting teams assemble customized solutions best adapted to client needs with such an extensive domestic network, we are the ideal partner for clients requiring specialist employees across the country.</p>
		<a href="../is-solutionsllc/services/commercial-engineering/" class="da-link">Read more</a>
		<div class="da-img"><img src="../is-solutionsllc/wp-content/plugins/wp-parallax-content-slider/images/commercialEngineer.png" alt="Commercial Engineering" /></div>
	</div>
	<nav class="da-arrows">
		<span class="da-arrows-prev"></span>
		<span class="da-arrows-next"></span>
	</nav>
</div>
STATICOUTPUT;

?>