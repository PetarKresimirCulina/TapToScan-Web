<footer>
	<div class="container">
		<div class="row">
		
			<div class="col-md-4 col-md-push-4 text-center footer-info">
				<a href="#"><i class="fa fa-facebook-official footer-social" aria-hidden="true"></i></a>
				<a href="#"><i class="fa fa-twitter-square footer-social" aria-hidden="true"></i></a>
				<a href="#"><i class="fa fa-envelope footer-social" aria-hidden="true"></i></a>
			</div>
			
			<div class="col-md-4 col-md-pull-4 text-left small footer-info">
				<ul>
					<li><a href="{{ route('page.index', App::getLocale()) }}">@lang('navbar.home')</a></li>
					<li><a href="{{ route('page.business', App::getLocale()) }}">@lang('navbar.forBarsAndPubs')</a></li>
					<!-- <li><a href="{{ route('page.features', App::getLocale()) }}">@lang('navbar.features')</a></li> -->
					<li><a href="{{ route('page.about', App::getLocale()) }}">@lang('navbar.about')</a></li>
					<li><a href="{{ route('page.contact', App::getLocale()) }}">@lang('navbar.contact')</a></li>
				</ul>
			</div>

			<div class="col-md-4 text-right small footer-info">
				<ul>
					<li><a href="{{ route('page.terms', App::getLocale()) }}">@lang('navbar.terms')</a></li>
					<li><a href="{{ route('page.privacy', App::getLocale()) }}">@lang('navbar.privacy')</a></li>
				</ul>
			</div>
		</div>
		
		<div class="divider-xs"></div>
		<div class="row">
			<div class="col-md-12 text-center small">
				<p>Copyright &copy; 2017 TapToScan.com. @lang('navbar.copyright')</br><a href="http://pkculina.com" target="_blank">Designed by pkculina.com</a></p>
			</div>
		</div>
	</div>
	
</footer>

