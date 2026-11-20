@php($tf = Admin::twoFactor())
@php($policyLocked = ! $panelEnabled || ! $canStoreMode)

@if(! $canStore)
    <div class="glass-alert-warning mb-5">
        <i class="icon-exclamation-triangle mt-0.5"></i>
        <div class="flex-1 text-sm">{{ $tf->text('not_migrated') }}</div>
    </div>
@elseif($mode === 'off')
    <div class="glass-alert-info mb-5">
        <i class="icon-info-circle mt-0.5"></i>
        <div class="flex-1 text-sm">{{ $tf->text('mode_off_notice') }}</div>
    </div>
@elseif($mode === 'required' && ! $enabled)
    <div class="glass-alert-warning mb-5">
        <i class="icon-exclamation-triangle mt-0.5"></i>
        <div class="flex-1 text-sm">{{ $tf->text('required_notice') }}</div>
    </div>
@endif

<div class="grid gap-5 {{ $canManagePolicy ? 'lg:grid-cols-12' : 'lg:grid-cols-3' }}">
    <div class="glass-card p-5 flex flex-col gap-4 {{ $canManagePolicy ? 'lg:col-span-7' : 'lg:col-span-2' }}">
        <div class="flex flex-wrap items-center justify-between gap-2">
            <h3 class="flex items-center gap-2 text-base font-semibold text-gray-800">
                <span class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center"><i class="icon-shield-alt"></i></span>
                {{ $tf->text('title') }}
            </h3>
            @if($enabled)
                <span class="badge bg-success">{{ $tf->text('status_on') }}</span>
            @elseif($pending)
                <span class="badge bg-warning">{{ $tf->text('status_pending') }}</span>
            @else
                <span class="badge bg-secondary">{{ $tf->text('status_off') }}</span>
            @endif
        </div>

        <p class="text-sm text-gray-600">{{ $tf->text('intro') }}</p>

        @if(count($recoveryCodes))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50/80 p-4">
                <h4 class="flex items-center gap-2 text-sm font-semibold text-emerald-800"><i class="icon-key"></i> {{ $tf->text('recovery_codes') }}</h4>
                <p class="text-xs text-emerald-700 mt-1 mb-3">{{ $tf->text('recovery_codes_help') }}</p>
                <div class="grid grid-cols-2 gap-1.5 font-mono text-sm">
                    @foreach($recoveryCodes as $code)
                        <code class="select-all rounded-md bg-white/80 border border-emerald-100 px-2 py-1 text-gray-800">{{ $code }}</code>
                    @endforeach
                </div>
            </div>
        @endif

        @if($enabled)
            <p class="flex items-center gap-2 text-sm text-emerald-700"><i class="icon-check-circle"></i> {{ $tf->text('enabled_help') }}</p>
            <p class="text-xs text-gray-500">{{ $tf->text('recovery_codes_left', ['count' => $recoveryCodesLeft]) }}</p>

            @if($errors->has('password'))
                <div class="glass-alert-danger">
                    <i class="icon-times-circle mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ $errors->first('password') }}</div>
                </div>
            @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <form method="POST" action="{{ route('admin.two-factor.recovery-codes') }}" class="flex flex-col gap-2">
                    @csrf
                    <label class="text-xs font-medium text-gray-600" for="regenerate-password">{{ $tf->text('current_password') }}</label>
                    <input type="password" class="glass-input" id="regenerate-password" name="password" autocomplete="current-password" required>
                    <button type="submit" class="glass-btn-secondary justify-center"><i class="icon-redo"></i> {{ $tf->text('regenerate') }}</button>
                </form>
                @if($mode !== 'required')
                    <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="flex flex-col gap-2">
                        @csrf
                        @method('DELETE')
                        <label class="text-xs font-medium text-gray-600" for="disable-password">{{ $tf->text('current_password') }}</label>
                        <input type="password" class="glass-input" id="disable-password" name="password" autocomplete="current-password" required>
                        <button type="submit" class="glass-btn-danger justify-center"><i class="icon-power-off"></i> {{ $tf->text('disable') }}</button>
                    </form>
                @endif
            </div>
        @elseif($pending)
            <div class="grid gap-5 sm:grid-cols-5 items-start">
                <div class="sm:col-span-2 flex justify-center">
                    <div class="p-2 bg-white rounded-xl border border-gray-200 shadow-sm">{!! $qrCode !!}</div>
                </div>
                <div class="sm:col-span-3 flex flex-col gap-2">
                    <p class="text-sm font-medium text-gray-800">{{ $tf->text('setup_scan') }}</p>
                    <p class="text-xs text-gray-500">{{ $tf->text('setup_manual') }}</p>
                    <code class="select-all font-mono text-sm rounded-lg bg-gray-100/80 border border-gray-200 px-3 py-2 text-gray-800 break-all">{{ trim(chunk_split($secret, 4, ' ')) }}</code>

                    <form method="POST" action="{{ route('admin.two-factor.confirm') }}" class="flex flex-col gap-2 mt-2">
                        @csrf
                        @method('PUT')
                        <label for="code" class="text-sm font-medium text-gray-800">{{ $tf->text('setup_confirm') }}</label>
                        @if($errors->has('code'))
                            <div class="glass-alert-danger">
                                <i class="icon-times-circle mt-0.5"></i>
                                <div class="flex-1 text-sm">{{ $errors->first('code') }}</div>
                            </div>
                        @endif
                        <input type="text" class="glass-input font-mono tracking-[0.3em] text-lg" id="code" name="code" inputmode="numeric" autocomplete="one-time-code" pattern="[0-9 ]*" maxlength="7" placeholder="000000" required autofocus>
                        <div class="flex flex-wrap items-center gap-3">
                            <button type="submit" class="glass-btn-primary"><i class="icon-check"></i> {{ $tf->text('confirm') }}</button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('admin.two-factor.disable') }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-sm text-gray-500 hover:text-gray-800 underline underline-offset-2">{{ $tf->text('cancel') }}</button>
                    </form>
                </div>
            </div>
        @else
            <form method="POST" action="{{ route('admin.two-factor.enable') }}">
                @csrf
                <button type="submit" class="glass-btn-primary" {{ $canStore && $mode !== 'off' ? '' : 'disabled' }}>
                    <i class="icon-qrcode"></i> {{ $tf->text('enable') }}
                </button>
            </form>
        @endif
    </div>

    @if($canManagePolicy)
        <div class="glass-card p-5 flex flex-col gap-4 lg:col-span-5">
            <h3 class="flex items-center gap-2 text-base font-semibold text-gray-800">
                <span class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center"><i class="icon-user-shield"></i></span>
                {{ $tf->text('policy') }}
            </h3>
            <p class="text-sm text-gray-600">{{ $tf->text('policy_help') }}</p>

            @if(! $panelEnabled)
                <div class="glass-alert-warning">
                    <i class="icon-lock mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ $tf->text('panel_disabled') }}</div>
                </div>
            @elseif(! $canStoreMode)
                <div class="glass-alert-warning">
                    <i class="icon-exclamation-triangle mt-0.5"></i>
                    <div class="flex-1 text-sm">{{ $tf->text('settings_not_migrated') }}</div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.two-factor.policy') }}" class="flex flex-col gap-2">
                @csrf
                @method('PUT')
                @foreach(['off', 'optional', 'required'] as $option)
                    <label for="two-factor-mode-{{ $option }}" class="flex items-start gap-3 rounded-xl border px-3 py-2.5 cursor-pointer transition-colors {{ $mode === $option ? 'border-primary-300 bg-primary-50/70' : 'border-gray-200 bg-white/60 hover:bg-white/90' }}">
                        <input type="radio" class="mt-1 text-primary-600 focus:ring-primary-500" name="mode" id="two-factor-mode-{{ $option }}" value="{{ $option }}" {{ $mode === $option ? 'checked' : '' }} {{ $policyLocked || ! $canStore ? 'disabled' : '' }}>
                        <span>
                            <span class="block text-sm font-semibold text-gray-800">{{ $tf->text('mode_'.$option) }}</span>
                            <span class="block text-xs text-gray-500">{{ $tf->text('mode_'.$option.'_help') }}</span>
                        </span>
                    </label>
                @endforeach
                <div class="pt-1">
                    <button type="submit" class="glass-btn-primary" {{ $policyLocked || ! $canStore ? 'disabled' : '' }}>{{ $tf->text('save') }}</button>
                </div>
            </form>

            <div class="text-xs text-gray-500 space-y-1">
                <p>{{ $storedMode ? $tf->text('source_panel') : $tf->text('source_config') }}</p>
                <p>{{ $tf->text('users_enabled', ['count' => $usersEnabled, 'total' => $usersTotal]) }}</p>
            </div>

            @if($storedMode && ! $policyLocked)
                <form method="POST" action="{{ route('admin.two-factor.policy.reset') }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="glass-btn-secondary glass-btn-sm"><i class="icon-undo"></i> {{ $tf->text('use_config') }}</button>
                </form>
            @endif
        </div>
    @endif
</div>
