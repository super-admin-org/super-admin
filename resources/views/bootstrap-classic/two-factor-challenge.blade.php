<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>{{config('admin.title')}} | {{ Admin::twoFactor()->text('title') }}</title>
		<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

		@if(!is_null($favicon = Admin::favicon()))
		<link rel="shortcut icon" href="{{$favicon}}">
		@endif

		<link rel="stylesheet" href="{{ Admin::asset("super-admin/css/styles.css")}}">
	</head>
	<body class="bg-light" @if(config('admin.login_background_image'))style="background: url({{config('admin.login_background_image')}}) no-repeat;background-size: cover;"@endif>
		<div class="d-flex justify-content-center align-items-center h-100">
			<div class="container m-4" style="max-width:400px;">
				<h1 class="text-center mb-3 h2"><a class="text-decoration-none text-dark" href="{{ admin_url('/') }}">{{config('admin.name')}}</a></h1>
				<div class="bg-body p-4 shadow-sm rounded-3">

					<h2 class="h5 mb-2"><i class="icon-shield-alt"></i> {{ Admin::twoFactor()->text('title') }}</h2>
					<p class="text-muted small">{{ Admin::twoFactor()->text('challenge_help', ['name' => $account]) }}</p>

					<form action="{{ admin_url('auth/two-factor/challenge') }}" method="post">
						<input type="hidden" name="_token" value="{{ csrf_token() }}">

						@if($errors->has('code'))
							<div class="alert alert-danger">{{ $errors->first('code') }}</div>
						@endif

						<label for="code" class="form-label">{{ Admin::twoFactor()->text('code') }}</label>
						<div class="input-group mb-3">
							<span class="input-group-text"><i class="icon-mobile-alt"></i></span>
							<input type="text" class="form-control form-control-lg font-monospace" name="code" id="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9 ]*" maxlength="7" placeholder="000000" required autofocus>
						</div>

						<button type="submit" class="btn btn-primary w-100">{{ Admin::twoFactor()->text('verify') }}</button>
					</form>

					<details class="mt-4" @if($errors->has('recovery_code')) open @endif>
						<summary class="small text-muted">{{ Admin::twoFactor()->text('use_recovery_code') }}</summary>

						<form action="{{ admin_url('auth/two-factor/challenge') }}" method="post" class="mt-2">
							<input type="hidden" name="_token" value="{{ csrf_token() }}">

							<p class="text-muted small mb-2">{{ Admin::twoFactor()->text('challenge_recovery_help') }}</p>

							@if($errors->has('recovery_code'))
								<div class="alert alert-danger">{{ $errors->first('recovery_code') }}</div>
							@endif

							<label for="recovery_code" class="form-label">{{ Admin::twoFactor()->text('recovery_code') }}</label>
							<input type="text" class="form-control font-monospace mb-3" name="recovery_code" id="recovery_code" autocomplete="off" placeholder="xxxxx-xxxxx" required>

							<button type="submit" class="btn btn-secondary w-100">{{ Admin::twoFactor()->text('verify') }}</button>
						</form>
					</details>

					<p class="text-center small mt-4 mb-0">
						<a href="{{ admin_url('auth/login') }}">{{ Admin::twoFactor()->text('back_to_login') }}</a>
					</p>
				</div>
			</div>
		</div>
	</body>
</html>
