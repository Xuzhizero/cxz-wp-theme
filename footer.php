<?php
/**
 * The footer template file
 *
 * @package CXZ_WP_Theme
 */
?>

<div class="search-wrapper">
	<div class="container">
		<div class="row">
			<div class="col col-12">
				<div class="search">
					<form class="search-form">
						<i aria-hidden="true" class="search-input-icon">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" style="vertical-align:middle"><path d="M456.69 421.39L362.6 327.3a173.81 173.81 0 0034.84-104.58C397.44 126.38 319.06 48 222.72 48S48 126.38 48 222.72s78.38 174.72 174.72 174.72A173.81 173.81 0 00327.3 362.6l94.09 94.09a25 25 0 0035.3-35.3zM97.92 222.72a124.8 124.8 0 11124.8 124.8 124.95 124.95 0 01-124.8-124.8z"/></svg>
						</i>
						<input class="search-field" type="text" placeholder="Type to search…">
						<button class="search-button" type="submit">
							<i aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="16" height="16" fill="currentColor" style="vertical-align:middle"><path d="M289.94 256l95-95A24 24 0 00351 127l-95 95-95-95a24 24 0 00-34 34l95 95-95 95a24 24 0 1034 34l95-95 95 95a24 24 0 0034-34z"/></svg></i>
						</button>
					</form>
					<div class="popular-wrapper">
						<h4 class="popular-title">Topics</h4>
						<span class="popular-tags post-tags">
							<a href="<?php echo esc_url( home_url( '/tag/the-business-pillar' ) ); ?>">Business</a>
							<a href="<?php echo esc_url( home_url( '/tag/the-religious-pillar' ) ); ?>">Religious</a>
							<a href="<?php echo esc_url( home_url( '/tag/technology' ) ); ?>">Technology</a>
							<a href="<?php echo esc_url( home_url( '/tag/the-awakening-pillar' ) ); ?>">Awakening</a>
							<a href="<?php echo esc_url( home_url( '/tag/tanka' ) ); ?>">Tanka</a>
						</span>
					</div>
					<div class="search-result"></div>
				</div>
				<button class="search-wrapper-close" aria-label="Close"></button>
			</div>
		</div>
	</div>
</div>

<section class="footer-widgets">
	<div class="container">
		<div class="row">
			<div class="col col-12">
				<div class="widget-box">
					<div class="row widget-row-aligned">
						<div class="col col-4 col-d-6 col-t-12 widget-col-equal">
							<div class="widget widget-recent widget-box-equal">
								<div class="widget__head">
									<h3 class="widget__title">Recent Posts</h3>
								</div>
								<div class="recent-post">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="recent-post__image" aria-hidden="true">
										<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/recent-01.png' ); ?>" alt="Recent Post" loading="lazy">
									</a>
									<div class="recent-post__content">
										<time class="recent-post__date" datetime="2025-12-30">Dec 30, 2025</time>
										<h4 class="recent-post__title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Log I. Open Source Log of an AI-Native Company: Data vs. Privacy</a></h4>
									</div>
								</div>
								<div class="recent-post">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="recent-post__image" aria-hidden="true">
										<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/recent-02.png' ); ?>" alt="Recent Post" loading="lazy">
									</a>
									<div class="recent-post__content">
										<time class="recent-post__date" datetime="2025-12-29">Dec 29, 2025</time>
										<h4 class="recent-post__title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">II. Pure Land</a></h4>
									</div>
								</div>
								<div class="recent-post">
									<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="recent-post__image" aria-hidden="true">
										<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/recent-03.png' ); ?>" alt="Recent Post" loading="lazy">
									</a>
									<div class="recent-post__content">
										<time class="recent-post__date" datetime="2025-12-26">Dec 26, 2025</time>
										<h4 class="recent-post__title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>">III. I Choose, I Shoulder, Therefore I Am</a></h4>
									</div>
								</div>
							</div>
						</div>
						<div class="col col-4 col-d-6 col-t-12 widget-col-equal">
							<div class="widget widget-newsletter widget-box-equal">
								<div class="widget__head">
									<h3 class="widget__title">Subscribe For More</h3>
								</div>
								<div class="newsletter__content newsletter-iframe-container">
									<iframe class="newsletter-iframe" src="https://mindful-palette-322177.framer.app/newsletter-chennative" frameborder="0" loading="lazy"></iframe>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<footer class="footer">
	<div class="container">
		<div class="row">
			<div class="col col-12">
				<div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-end; padding-top: 20px; padding-bottom: 20px;">
					<div style="text-align: left;">
						<h2 style="font-size: 48px; font-weight: bold; margin-bottom: 30px; color: var(--heading-font-color); font-family: 'Libre Baskerville', serif;">Contact</h2>
						<ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 15px;">
							<li style="display: flex; align-items: center;">
								<span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24" fill="#0077b5"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.28c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>
								</span>
								<a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">Xuzhi's Linkedin</a>
							</li>
							<li style="display: flex; align-items: center;">
								<span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" width="24" height="24" fill="#FF0000"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 42.155 48.284 48.477 42.591 11.412 213.371 11.412 213.371 11.412s170.78 0 213.371-11.412c23.497-6.322 42.003-24.827 48.284-48.477 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>
								</span>
								<a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">Follow Xuzhi on YouTube</a>
							</li>
							<li style="display: flex; align-items: center;">
								<span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="24" height="24" fill="#C92027"><path d="M278.9 511.5l-61-17.7c-6.4-1.8-10-8.5-8.2-14.9L346.2 8.7c1.8-6.4 8.5-10 14.9-8.2l61 17.7c6.4 1.8 10 8.5 8.2 14.9L293.8 503.3c-1.8 6.4-8.5 10-14.9 8.2zm-114-112.2l60.9 17.7c6.4 1.8 10 8.5 8.2 14.9l-133.5 463.6c-1.8 6.4-8.5 10-14.9 8.2L24.7 486c-6.4-1.8-10-8.5-8.2-14.9l133.5-463.6c1.8-6.4 8.5-10 14.9-8.2zm369.3 0l-133.5 463.6c-1.8 6.4-10 14.9-8.2l-60.9-17.7c-6.4-1.8-10-8.5-8.2-14.9l133.5-463.6c1.8-6.4 8.5-10 14.9-8.2l60.9 17.7c6.4 1.8 10 8.5 8.2 14.9z"/></svg>
								</span>
								<a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">CSDN Technical Blog</a>
							</li>
							<li style="display: flex; align-items: center;">
								<span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="24" height="24" fill="#25b864"><path d="M448 360V24c0-13.3-10.7-24-24-24H96C43 0 0 43 0 96v320c0 53 43 96 96 96h328c13.3 0 24-10.7 24-24v-16c0-7.5-3.5-14.3-8.9-18.7-4.2-15.4-4.2-59.3 0-74.7 5.4-4.3 8.9-11.1 8.9-18.6zM128 134c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm0 64c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm253.4 250H96c-17.7 0-32-14.3-32-32 0-17.6 14.4-32 32-32h285.4c-1.9 17.1-1.9 46.9 0 64z"/></svg>
								</span>
								<a href="#" style="font-size: 18px; color: var(--text-color); text-decoration: none;">Check out my Yuque Personal Docs</a>
							</li>
							<li style="display: flex; align-items: center;">
								<span style="width: 24px; height: 24px; display: inline-flex; justify-content: center; align-items: center; margin-right: 12px;">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="24" height="24" fill="#2196F3"><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/></svg>
								</span>
								<a href="mailto:cxzzero@126.com" style="font-size: 18px; color: var(--text-color); text-decoration: none;">cxzzero@126.com</a>
							</li>
						</ul>
					</div>
					<div style="text-align: right;">
						<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/assets/img/Profile.jpg' ); ?>" alt="Profile Picture" style="max-width: 100%; width: 350px; height: auto; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
