<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Crear una cuenta')" :description="__('Ingresa tus datos a continuación para crear tu cuenta')" />

    <!-- Estado de la sesión -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Nombre completo -->
        <flux:input
            wire:model="nombreCompleto"
            :label="__('Nombre completo')"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Nombre completo')"
        />

        <!-- Identidad -->
        <flux:input
            wire:model="identidad"
            :label="__('Número de identidad')"
            type="text"
            required
            maxlength="13"
            :placeholder="__('Ej: 0801199912345')"
        />

        <!-- Fecha de nacimiento -->
        <flux:input
            wire:model="fechaNacimiento"
            :label="__('Fecha de nacimiento')"
            type="date"
            required
        />

        <!-- Teléfono -->
        <flux:input
            wire:model="telefono"
            :label="__('Teléfono')"
            type="text"
            required
            maxlength="8"
            :placeholder="__('Ej: 98765432')"
        />

        <!-- Correo electrónico -->
        <flux:input
            wire:model="email"
            :label="__('Correo electrónico')"
            type="text"
            required
            autocomplete="email"
            placeholder="correo@ejemplo.com"
        />

        <!-- Contraseña -->
        <flux:input
            wire:model="password"
            :label="__('Contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Contraseña')"
            viewable
        />

        <!-- Confirmar contraseña -->
        <flux:input
            wire:model="password_confirmation"
            :label="__('Confirmar contraseña')"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Confirmar contraseña')"
            viewable
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Crear cuenta') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-zinc-600 dark:text-zinc-400">
        <span>{{ __('¿Ya tienes una cuenta?') }}</span>
        <flux:link :href="route('login')" wire:navigate>{{ __('Inicia sesión') }}</flux:link>
    </div>
</div>
