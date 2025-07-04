import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Pages/**/*.php',
        './app/Filament/Widgets/**/*.php',
        './resources/views/filament/pages/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
        './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
    ],
}
