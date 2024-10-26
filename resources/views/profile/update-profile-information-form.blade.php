<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Profile Information') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Update your account\'s profile information and email address.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->

        @if (!Auth::user()->avatar)
            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                    <!-- Profile Photo File Input -->
                    <input type="file" id="photo" class="hidden"
                           wire:model.live="photo"
                           x-ref="photo"
                           x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            "/>

                    <x-label for="photo" value="{{ __('Photo') }}"/>

                    <!-- Current Profile Photo -->
                    <div class="mt-2" x-show="! photoPreview">
                        <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->given_name }}"
                             class="rounded-full h-20 w-20 object-cover">
                    </div>

                    <!-- New Profile Photo Preview -->
                    <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                    </div>

                    <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                        {{ __('Select A New Photo') }}
                    </x-secondary-button>

                    @if ($this->user->profile_photo_path)
                        <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                            {{ __('Remove Photo') }}
                        </x-secondary-button>
                    @endif

                    <x-input-error for="photo" class="mt-2"/>
                </div>
            @endif
        @else
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">

                <x-label for="photo" value="{{ __('Photo') }}"/>

                <!-- Current Google Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->avatar }}" alt="{{ $this->user->given_name }}"
                         class="rounded-full h-20 w-20 object-cover">
                </div>
            </div>
        @endif
        @if(Auth::user()->google_id)
            <!-- Given Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="given_name" value="{{ __('First Name') }}"/>
                <x-input id="given_name" type="text"
                         class="mt-1 block w-full disabled:bg-gray-100 disabled:text-gray-500"
                         :disabled="true" wire:model="state.given_name" required
                         autocomplete="given_name"/>
                <x-input-error for="given_name" class="mt-2"/>
            </div>
            <!-- Family Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="family_name" value="{{ __('Last Name') }}"/>
                <x-input id="family_name" type="text"
                         class="mt-1 block w-full disabled:bg-gray-100 disabled:text-gray-500"
                         :disabled="true" wire:model="state.family_name" required
                         autocomplete="family_name"/>
                <x-input-error for="family_name" class="mt-2"/>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="email" value="{{ __('Email') }}"/>
                <x-input id="email" type="email" class="mt-1 block w-full disabled:bg-gray-100 disabled:text-gray-500"
                         :disabled="true" wire:model="state.email" required
                         autocomplete="username"/>
                <x-input-error for="email" class="mt-2"/>

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2">
                        {{ __('Your email address is unverified.') }}

                        <button type="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                wire:click.prevent="sendEmailVerification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if ($this->verificationLinkSent)
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                @endif
            </div>
        @else
            <!-- Given Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="given_name" value="{{ __('First Name') }}"/>
                <x-input id="given_name" type="text"
                         class="mt-1 block w-full disabled:bg-gray-100 disabled:text-gray-500"
                         wire:model="state.given_name" required
                         autocomplete="given_name"/>
                <x-input-error for="given_name" class="mt-2"/>
            </div>
            <!-- Family Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="family_name" value="{{ __('Last Name') }}"/>
                <x-input id="family_name" type="text"
                         class="mt-1 block w-full disabled:bg-gray-100 disabled:text-gray-500"
                         wire:model="state.family_name" required
                         autocomplete="family_name"/>
                <x-input-error for="family_name" class="mt-2"/>
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="email" value="{{ __('Email') }}"/>
                <x-input id="email" type="email" class="mt-1 block w-full disabled:bg-gray-100 disabled:text-gray-500"
                         wire:model="state.email" required
                         autocomplete="username"/>
                <x-input-error for="email" class="mt-2"/>

                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                    <p class="text-sm mt-2">
                        {{ __('Your email address is unverified.') }}

                        <button type="button"
                                class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                wire:click.prevent="sendEmailVerification">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if ($this->verificationLinkSent)
                        <p class="mt-2 font-medium text-sm text-green-600">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                @endif
            </div>
        @endif
    </x-slot>

    @if(Auth::user()->google_id)
        <x-slot name="actions">
            <x-action-message class="me-3 hidden" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" class="hidden" wire:target="photo">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    @else
        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>

            <x-button wire:loading.attr="disabled" wire:target="photo">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    @endif
</x-form-section>
