<!DOCTYPE HTML>
<!--
	Arcana by HTML5 UP
	html5up.net | @ajlkn
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>okhs.tennis</title>
        <script src="https://kit.fontawesome.com/a6b4f8b08f.js" crossorigin="anonymous"></script>
        <link rel="icon" href="{{asset('images/tennis-ball.png')}}">
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="{{asset('css/main.css')}}" />
        <meta name="theme-color" content="#333" />
        <!-- Chrome, Firefox OS and Opera -->
        <meta name="theme-color" content="#333">
        <!-- Windows Phone -->
        <meta name="msapplication-navbutton-color" content="#333">
        <!-- iOS Safari -->
        <meta name="apple-mobile-web-app-status-bar-style" content="#333">
	</head>
	<body class="is-preload">
		<div id="page-wrapper">
			<!-- Header -->
				<div id="header">

					<!-- Logo -->


					<!-- Nav -->
						<nav id="nav">
							<ul>
								<li class="current"><a class="navbar-brand" href="{{ url('/') }}">
                                        {{ config('home', 'okhs.tennis') }}
                                    </a></li>
{{--								<li>--}}
{{--									<a href="#">Dropdown</a>--}}
{{--									<ul>--}}
{{--										<li><a href="#">Lorem dolor</a></li>--}}
{{--										<li><a href="#">Magna phasellus</a></li>--}}
{{--										<li><a href="#">Etiam sed tempus</a></li>--}}
{{--										<li>--}}
{{--											<a href="#">Submenu</a>--}}
{{--											<ul>--}}
{{--												<li><a href="#">Lorem dolor</a></li>--}}
{{--												<li><a href="#">Phasellus magna</a></li>--}}
{{--												<li><a href="#">Magna phasellus</a></li>--}}
{{--												<li><a href="#">Etiam nisl</a></li>--}}
{{--												<li><a href="#">Veroeros feugiat</a></li>--}}
{{--											</ul>--}}
{{--										</li>--}}
{{--										<li><a href="#">Veroeros feugiat</a></li>--}}
{{--									</ul>--}}
{{--								</li>--}}

								<li><a class="navbar-brand" href="{{ url('/createtournament') }}">
                                        {{ config('createtournament', 'Create Tournament') }}
                                    </a></li>
								<li><a class="navbar-brand" href="{{ url('/tournaments') }}">
                                        {{ config('tournaments', 'View Tournaments') }}
                                    </a></li>
								<li><a class="navbar-brand" href="{{ url('/players') }}">
                                        {{ config('players', 'Player Rankings') }}
                                    </a></li>
                                <li><a class="navbar-brand" href="{{ url('/schools') }}">
                                        {{ config('schools', 'View Schools') }}
                                    </a></li>

                                @guest
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                    </li>
                                    @if (Route::has('register'))
                                        <li class="nav-item">
                                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                        </li>
                                    @endif
                                @else
                                    <li class="nav-item dropdown">
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                {{ __('Logout') }}
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                @csrf
                                            </form>
                                        </div>
                                    </li>
                                @endguest
							</ul>

						</nav>

				</div>

			<!-- Banner -->
{{--				<section id="banner">--}}
{{--					<header>--}}
{{--						<h2>Arcana: <em>A responsive site template freebie by <a href="http://html5up.net">HTML5 UP</a></em></h2>--}}
{{--						<a href="#" class="button">Learn More</a>--}}
{{--					</header>--}}
{{--				</section>--}}

            <section id="banner">
                <p id="banner-text">Oklahoma High School Tennis</p>

            </section>
            <section>

                </section>


			<!-- Highlights -->
				<section class="wrapper style1">
					<div class="container">
						<div class="row gtr-200">
							<section class="col-3 col-12-narrower">
								<div class="box highlight">
									<i class="icon solid major fa-trophy"></i>
									<h3>Organize Tournaments</h3>
									<p>Create, organize, and join tournaments.  Invite other schools.  Get your schedule organized for the year in a quick and clean fashion.</p>
								</div>
							</section>
							<section class="col-3 col-12-narrower">
								<div class="box highlight">
									<i class="icon solid major fa-sitemap"></i>
									<h3>Run Tournament Brackets</h3>
									<p>Run your tournament brackets.  All of them.
                                        Selecting winners, saving scores, and advancing each bracket (Girls, Boys, One Singles, Two Singles, One Doubles, Two Doubles) can all be done here.</p>
								</div>
							</section>
							<section class="col-3 col-12-narrower">
								<div class="box highlight">
									<i class="icon solid major fa-line-chart"></i>
									<h3>View Player Rankings</h3>
									<p>Each and every result from tournaments are saved.  This means tournaments can be automatically seeded once there is enough match history.
                                        It also means players and coaches alike can see how they rank against the competition state wide.</p>
								</div>
							</section>
                            <section class="col-3 col-12-narrower">
                                <div class="box highlight">
                                    <i class="icon solid major fa-users"></i>
                                    <h3>For The Fans</h3>
                                    <p>Find what court your player(s) are on so you can go watch them play or check on how they are doing from afar.  As the tournament progresses, you will be able to get live updates on results and see who they play next.</p>
                                </div>
                            </section>
						</div>
					</div>
				</section>

			<!-- Gigantic Heading -->
				<section class="wrapper style2">
					<div class="container">
						<header class="major">
							<h2>Modernizing the game</h2>
							<p id="subheader-main-page">A project made with passion, designed and created by a former Oklahoma High School Player and Coach</p>
						</header>
					</div>
				</section>

			<!-- Posts -->
{{--				<section class="wrapper style1">--}}
{{--					<div class="container">--}}
{{--						<div class="row">--}}
{{--							<section class="col-6 col-12-narrower">--}}
{{--								<div class="box post">--}}
{{--									<a href="#" class="image left"><img src="images/pic01.jpg" alt="" /></a>--}}
{{--									<div class="inner">--}}
{{--										<h3>The First Thing</h3>--}}
{{--										<p>Duis neque nisi, dapibus sed mattis et quis, nibh. Sed et dapibus nisl amet mattis, sed a rutrum accumsan sed. Suspendisse eu.</p>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</section>--}}
{{--							<section class="col-6 col-12-narrower">--}}
{{--								<div class="box post">--}}
{{--									<a href="#" class="image left"><img src="images/pic02.jpg" alt="" /></a>--}}
{{--									<div class="inner">--}}
{{--										<h3>The Second Thing</h3>--}}
{{--										<p>Duis neque nisi, dapibus sed mattis et quis, nibh. Sed et dapibus nisl amet mattis, sed a rutrum accumsan sed. Suspendisse eu.</p>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</section>--}}
{{--						</div>--}}
{{--						<div class="row">--}}
{{--							<section class="col-6 col-12-narrower">--}}
{{--								<div class="box post">--}}
{{--									<a href="#" class="image left"><img src="images/pic03.jpg" alt="" /></a>--}}
{{--									<div class="inner">--}}
{{--										<h3>The Third Thing</h3>--}}
{{--										<p>Duis neque nisi, dapibus sed mattis et quis, nibh. Sed et dapibus nisl amet mattis, sed a rutrum accumsan sed. Suspendisse eu.</p>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</section>--}}
{{--							<section class="col-6 col-12-narrower">--}}
{{--								<div class="box post">--}}
{{--									<a href="#" class="image left"><img src="images/pic04.jpg" alt="" /></a>--}}
{{--									<div class="inner">--}}
{{--										<h3>The Fourth Thing</h3>--}}
{{--										<p>Duis neque nisi, dapibus sed mattis et quis, nibh. Sed et dapibus nisl amet mattis, sed a rutrum accumsan sed. Suspendisse eu.</p>--}}
{{--									</div>--}}
{{--								</div>--}}
{{--							</section>--}}
{{--						</div>--}}
{{--					</div>--}}
{{--				</section>--}}

{{--			<!-- CTA -->--}}
{{--				<section id="cta" class="wrapper style3">--}}
{{--					<div class="container">--}}
{{--						<header>--}}
{{--							<h2>Are you ready to continue your quest?</h2>--}}
{{--							<a href="#" class="button">Insert Coin</a>--}}
{{--						</header>--}}
{{--					</div>--}}
{{--				</section>--}}

{{--			<!-- Footer -->--}}
{{--				<div id="footer">--}}
{{--					<div class="container">--}}
{{--						<div class="row">--}}
{{--							<section class="col-3 col-6-narrower col-12-mobilep">--}}
{{--								<h3>Links to Stuff</h3>--}}
{{--								<ul class="links">--}}
{{--									<li><a href="#">Mattis et quis rutrum</a></li>--}}
{{--									<li><a href="#">Suspendisse amet varius</a></li>--}}
{{--									<li><a href="#">Sed et dapibus quis</a></li>--}}
{{--									<li><a href="#">Rutrum accumsan dolor</a></li>--}}
{{--									<li><a href="#">Mattis rutrum accumsan</a></li>--}}
{{--									<li><a href="#">Suspendisse varius nibh</a></li>--}}
{{--									<li><a href="#">Sed et dapibus mattis</a></li>--}}
{{--								</ul>--}}
{{--							</section>--}}
{{--							<section class="col-3 col-6-narrower col-12-mobilep">--}}
{{--								<h3>More Links to Stuff</h3>--}}
{{--								<ul class="links">--}}
{{--									<li><a href="#">Duis neque nisi dapibus</a></li>--}}
{{--									<li><a href="#">Sed et dapibus quis</a></li>--}}
{{--									<li><a href="#">Rutrum accumsan sed</a></li>--}}
{{--									<li><a href="#">Mattis et sed accumsan</a></li>--}}
{{--									<li><a href="#">Duis neque nisi sed</a></li>--}}
{{--									<li><a href="#">Sed et dapibus quis</a></li>--}}
{{--									<li><a href="#">Rutrum amet varius</a></li>--}}
{{--								</ul>--}}
{{--							</section>--}}
{{--							<section class="col-6 col-12-narrower">--}}
{{--								<h3>Get In Touch</h3>--}}
{{--								<form>--}}
{{--									<div class="row gtr-50">--}}
{{--										<div class="col-6 col-12-mobilep">--}}
{{--											<input type="text" name="name" id="name" placeholder="Name" />--}}
{{--										</div>--}}
{{--										<div class="col-6 col-12-mobilep">--}}
{{--											<input type="email" name="email" id="email" placeholder="Email" />--}}
{{--										</div>--}}
{{--										<div class="col-12">--}}
{{--											<textarea name="message" id="message" placeholder="Message" rows="5"></textarea>--}}
{{--										</div>--}}
{{--										<div class="col-12">--}}
{{--											<ul class="actions">--}}
{{--												<li><input type="submit" class="button alt" value="Send Message" /></li>--}}
{{--											</ul>--}}
{{--										</div>--}}
{{--									</div>--}}
{{--								</form>--}}
{{--							</section>--}}
{{--						</div>--}}
{{--					</div>--}}

{{--					<!-- Icons -->--}}
{{--						<ul class="icons">--}}
{{--							<li><a href="#" class="icon brands fa-twitter"><span class="label">Twitter</span></a></li>--}}
{{--							<li><a href="#" class="icon brands fa-facebook-f"><span class="label">Facebook</span></a></li>--}}
{{--							<li><a href="#" class="icon brands fa-github"><span class="label">GitHub</span></a></li>--}}
{{--							<li><a href="#" class="icon brands fa-linkedin-in"><span class="label">LinkedIn</span></a></li>--}}
{{--							<li><a href="#" class="icon brands fa-google-plus-g"><span class="label">Google+</span></a></li>--}}
{{--						</ul>--}}

{{--					<!-- Copyright -->--}}
{{--						<div class="copyright">--}}
{{--							<ul class="menu">--}}
{{--								<li>&copy; Untitled. All rights reserved</li><li>Design: <a href="http://html5up.net">HTML5 UP</a></li>--}}
{{--							</ul>--}}
{{--						</div>--}}

{{--				</div>--}}

		</div>

		<!-- Scripts -->
			<script src="{{asset('js/jquery.min.js')}}"></script>
			<script src="{{asset('js/jquery.dropotron.min.js')}}"></script>
			<script src="{{asset('js/browser.min.js')}}"></script>
			<script src="{{asset('js/breakpoints.min.js')}}"></script>
			<script src="{{asset('js/util.js')}}"></script>
        <script src="{{asset('js/main.js')}}"></script>

	</body>
</html>
