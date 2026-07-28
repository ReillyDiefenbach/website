<section class="hero dotted darken">
	<!-- Hintergrundvideo -->
	<video class="bg-video" autoplay="" muted="" loop="" playsinline="" preload="auto" aria-hidden="true">
		    <source src="/_assets/media/video/carlvon.mp4" type="video/mp4">
	</video>
	<div class="canvas">
		<div id="welcomeBox">
				<svg class="arrowDown" xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 30 30"><polygon points="15,30 6.25,5 23.75,5" /></svg>
				<svg class="carlvonSvg" version="1.1" id="carvon-svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
					 viewBox="0 0 443.7 52.1" style="enable-background:new 0 0 443.7 52.1;" xml:space="preserve">
				<g>
					<path d="M36.7,52.1c-14.8,0-25.9-11.4-25.9-25.9v-0.1C10.8,11.7,21.6,0,37.1,0C46.6,0,52.3,3.2,57,7.8l-7.1,8.1
						c-3.9-3.5-7.8-5.7-12.9-5.7c-8.5,0-14.6,7.1-14.6,15.7v0.1c0,8.6,6,15.8,14.6,15.8c5.8,0,9.3-2.3,13.3-5.9l7.1,7.1
						C52.1,48.7,46.4,52.1,36.7,52.1z"/>
					<path d="M114.5,51.3L109.9,40H88.6L84,51.3H72.6L94.3,0.5h10.2l21.6,50.8L114.5,51.3L114.5,51.3z M99.2,13.8l-6.7,16.3h13.4
						L99.2,13.8z"/>
					<path d="M175.3,51.3l-10.8-16.1h-8.7v16.1h-11.1V0.9h23c11.9,0,19,6.3,19,16.6v0.1c0,8.1-4.4,13.2-10.8,15.6l12.3,18L175.3,51.3
						L175.3,51.3z M175.5,18.1c0-4.8-3.3-7.2-8.7-7.2h-11v14.5H167c5.4,0,8.5-2.9,8.5-7.1V18.1z"/>
					<path d="M207.5,51.3V0.9h11.1v40.3h25.1v10.1L207.5,51.3L207.5,51.3z"/>
					<path d="M280.4,51.6h-9.8L250.3,0.9h12.2l13.2,35.5l13.2-35.5h12L280.4,51.6z"/>
					<path d="M340.6,52.1c-15.6,0-26.7-11.6-26.7-25.9v-0.1c0-14.3,11.3-26.1,26.9-26.1s26.7,11.6,26.7,25.9v0.1
						C367.5,40.4,356.2,52.1,340.6,52.1z M355.9,26.1c0-8.6-6.3-15.8-15.3-15.8s-15.1,7.1-15.1,15.7v0.1c0,8.6,6.3,15.8,15.3,15.8
						s15.1-7.1,15.1-15.7V26.1z"/>
					<path d="M423.5,51.3l-24.4-32v32h-10.9V0.9h10.2l23.6,31v-31h10.9v50.4L423.5,51.3L423.5,51.3z"/>
				</g>
				</svg>
			<div class="h2">Premium-class<br>Personality Analysis</div>
		</div>
	</div>
</section>



<section class="content">
	  <div class="wolf-test-content">
        	<div style="padding-top:600px;padding-bottom:600px;background:blue;height:70px;width:400px;text-align:center;justify-content:center;" class="imageFitter mx-auto mb-5">
			        	<img src="/_assets/self/king.png" />
			</div>
			<div class="mb-3 slogan mx-auto">carl von | analytica </div> 
      </div>
</section>
<section class="content">
	  <div class="wolf-test-content">
        	<div style="padding-top:600px;padding-bottom:600px;background:blue;height:70px;width:400px;text-align:center;justify-content:center;" class="imageFitter mx-auto mb-5">
			        	<img src="/_assets/self/king.png" />
			</div>
			<div class="mb-3 slogan mx-auto">carl von | analytica </div> 
      </div>
</section>
<style>
	.cinematicPage {
    position: relative;
    min-height: 220vh;
    background: #fff;
    overflow-x: hidden;
}

.cinematicHero {
    position: sticky;
    top: 0;
    height: 100vh;
    z-index: 1;
    overflow: hidden;
    background: #111;
}

.cinematicMedia {
    position: absolute;
    inset: 0;
    transform: scale(var(--hero-scale, 1));
    opacity: var(--hero-opacity, 1);
    filter:
        grayscale(var(--hero-gray, 0))
        brightness(var(--hero-bright, 1))
        blur(var(--hero-blur, 0px));
}

.cinematicMedia img,
.cinematicMedia video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cinematicFade {
    position: absolute;
    inset: 0;
    background: rgba(255,255,255,var(--hero-fade, 0));
    z-index: 2;
}

.cinematicTitle {
    position: absolute;
    left: 7vw;
    bottom: 12vh;
    z-index: 3;
    color: white;
    max-width: 900px;
    opacity: var(--title-opacity, 1);
    transform: translateY(var(--title-y, 0px));
}

.cinematicTitle h1 {
    margin: 0 0 1rem 0;
    font-size: clamp(3rem, 9vw, 9rem);
    line-height: .85;
    letter-spacing: -0.06em;
}

.cinematicTitle p {
    margin: 0;
    font-size: clamp(1.2rem, 2vw, 2rem);
}

.cinematicContent {
    position: relative;
    z-index: 5;
    margin-top: 85vh;
    background: white;
    border-radius: 2.5rem 2.5rem 0 0;
    padding: clamp(2rem, 6vw, 6rem);
    min-height: 100vh;
    box-shadow: 0 -40px 100px rgba(0,0,0,.18);
}
</style>
<script>
function initCarlvonHero($scope) {
    if (carlvonHeroScroll) {
        $(window).off('scroll', carlvonHeroScroll);
    }

    const hero = $scope.find('.hero').get(0);
    if (!hero) return;

    carlvonHeroScroll = function () {
        const rect = hero.getBoundingClientRect();

        let progress = Math.min(
            Math.max(-rect.top / window.innerHeight, 0),
            1
        );
        
        console.log('scrolling');

        hero.style.setProperty('--hero-scale', 1 - progress * 0.22);
        hero.style.setProperty('--hero-opacity', 1 - progress * 0.55);
        hero.style.setProperty('--hero-brightness', 1 + progress * 0.45);
        hero.style.setProperty('--hero-gray', progress * 0.65);
        hero.style.setProperty('--hero-blur', `${progress * 4}px`);

        hero.style.setProperty('--welcome-opacity', 1 - progress * 1.4);
        hero.style.setProperty('--welcome-y', `${progress * -90}px`);
        hero.style.setProperty('--welcome-scale', 1 - progress * 0.15);
    };

    $(window).on('scroll', carlvonHeroScroll);
    carlvonHeroScroll();
}

window.scroll(0,0);
initCarlvonHero($('#middle'));
</script>
