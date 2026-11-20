@php($tf = Admin::twoFactor())
@php($policyLocked = ! $panelEnabled || ! $canStoreMode)

@if(! $canStore)
    <div class="alert alert-warning d-flex gap-2"><i class="icon-exclamation-triangle mt-1"></i><div>{{ $tf->text('not_migrated') }}</div></div>
@elseif($mode === 'off')
    <div class="alert alert-info d-flex gap-2"><i class="icon-info-circle mt-1"></i><div>{{ $tf->text('mode_off_notice') }}</div></div>
@elseif($mode === 'required' && ! $enabled)
    <div class="alert alert-warning d-flex gap-2"><i class="icon-exclamation-triangle mt-1"></i><div>{{ $tf->text('required_notice') }}</div></div>
@endif

<div class="row g-4">
    <div class="{{ $canManagePolicy ? 'col-lg-7' : 'col-lg-8' }}">
        <div class="card h-100">
            <div class="card-body d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <h5 class="card-title mb-0"><i class="icon-shield-alt"></i> {{ $tf->text('title') }}</h5>
                    @if($enabled)
                        <span class="badge bg-success">{{ $tf->text('status_on') }}</span>
                    @elseif($pending)
                        <span class="badge bg-warning text-dark">{{ $tf->text('status_pending') }}</span>
                    @else
                        <span class="badge bg-secondary">{{ $tf->text('status_off') }}</span>
                    @endif
                </div>

                <p class="text-muted mb-0">{{ $tf->text('intro') }}</p>

                @if(count($recoveryCodes))
                    <div class="alert alert-success mb-0">
                        <h6 class="mb-1"><i class="icon-key"></i> {{ $tf->text('recovery_codes') }}</h6>
                        <p class="small mb-2">{{ $tf->text('recovery_codes_help') }}</p>
                        <div class="row g-1 font-monospace">
                            @foreach($recoveryCodes as $code)
                                <div class="col-6"><code class="user-select-all">{{ $code }}</code></div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($enabled)
                    <p class="text-success mb-0"><i class="icon-check-circle"></i> {{ $tf->text('enabled_help') }}</p>
                    <p class="small text-muted mb-0">{{ $tf->text('recovery_codes_left', ['count' => $recoveryCodesLeft]) }}</p>

                    @if($errors->has('password'))
                        <div class="alert alert-danger mb-0">{{ $errors->first('password') }}</div>
                    @endif

                    <div class="row g-3">
                        <div class="col-md-6">
                            <form method="POST" action="{{ route('admin.two-factor.recovery-codes') }}" class="m-0">
                                @csrf
                                <label class="form-label small" for="regenerate-password">{{ $tf->text('current_password') }}</label>
                                <input type="password" class="form-control mb-2" id="regenerate-password" name="password" autocomplete="current-password" required>
                                <button type="submit" class="btn btn-secondary w-100"><i class="icon-redo"></i> {{ $tf->text('regenerate') }}</button>
                            </form>
                        </div>
                        @if($mode !== 'required')
                            <div class="col-md-6">
                                <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <label class="form-label small" for="disable-password">{{ $tf->text('current_password') }}</label>
                                    <input type="password" class="form-control mb-2" id="disable-password" name="password" autocomplete="current-password" required>
                                    <button type="submit" class="btn btn-danger w-100"><i class="icon-power-off"></i> {{ $tf->text('disable') }}</button>
                                </form>
                            </div>
                        @endif
                    </div>
                @elseif($pending)
                    <div class="row g-3 align-items-start">
                        <div class="col-md-5 text-center">
                            <div class="d-inline-block p-2 bg-white border rounded">{!! $qrCode !!}</div>
                        </div>
                        <div class="col-md-7">
                            <p class="mb-1">{{ $tf->text('setup_scan') }}</p>
                            <p class="small text-muted mb-1">{{ $tf->text('setup_manual') }}</p>
                            <p><code class="user-select-all">{{ trim(chunk_split($secret, 4, ' ')) }}</code></p>

                            <form method="POST" action="{{ route('admin.two-factor.confirm') }}" class="m-0">
                                @csrf
                                @method('PUT')
                                <label for="code" class="form-label">{{ $tf->text('setup_confirm') }}</label>
                                @if($errors->has('code'))
                                    <div class="alert alert-danger py-2">{{ $errors->first('code') }}</div>
                                @endif
                                <input type="text" class="form-control font-monospace mb-2" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9 ]*" maxlength="7" placeholder="000000" required autofocus>
                                <button type="submit" class="btn btn-primary"><i class="icon-check"></i> {{ $tf->text('confirm') }}</button>
                            </form>

                            <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link px-0">{{ $tf->text('cancel') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.two-factor.enable') }}" class="m-0">
                        @csrf
                        <button type="submit" class="btn btn-primary" {{ $canStore && $mode !== 'off' ? '' : 'disabled' }}>
                            <i class="icon-qrcode"></i> {{ $tf->text('enable') }}
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    @if($canManagePolicy)
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body d-flex flex-column gap-3">
                    <h5 class="card-title mb-0"><i class="icon-user-shield"></i> {{ $tf->text('policy') }}</h5>
                    <p class="text-muted small mb-0">{{ $tf->text('policy_help') }}</p>

                    @if(! $panelEnabled)
                        <div class="alert alert-warning mb-0">{{ $tf->text('panel_disabled') }}</div>
                    @elseif(! $canStoreMode)
                        <div class="alert alert-warning mb-0">{{ $tf->text('settings_not_migrated') }}</div>
                    @endif

                    <form method="POST" action="{{ route('admin.two-factor.policy') }}" class="m-0">
                        @csrf
                        @method('PUT')
                        @foreach(['off', 'optional', 'required'] as $option)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="mode" id="two-factor-mode-{{ $option }}" value="{{ $option }}" {{ $mode === $option ? 'checked' : '' }} {{ $policyLocked || ! $canStore ? 'disabled' : '' }}>
                                <label class="form-check-label" for="two-factor-mode-{{ $option }}">
                                    <strong>{{ $tf->text('mode_'.$option) }}</strong>
                                    <span class="d-block small text-muted">{{ $tf->text('mode_'.$option.'_help') }}</span>
                                </label>
                            </div>
                        @endforeach
                        <button type="submit" class="btn btn-primary" {{ $policyLocked || ! $canStore ? 'disabled' : '' }}>{{ $tf->text('save') }}</button>
                    </form>

                    <div class="small text-muted">
                        <p class="mb-1">{{ $storedMode ? $tf->text('source_panel') : $tf->text('source_config') }}</p>
                        <p class="mb-0">{{ $tf->text('users_enabled', ['count' => $usersEnabled, 'total' => $usersTotal]) }}</p>
                    </div>

                    @if($storedMode && ! $policyLocked)
                        <form method="POST" action="{{ route('admin.two-factor.policy.reset') }}" class="m-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-light btn-sm"><i class="icon-undo"></i> {{ $tf->text('use_config') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif
</div>
