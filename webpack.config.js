const Encore = require('@symfony/webpack-encore');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    
    // Entrées principales
    .addEntry('app', './assets/app.js')
    
    // Entrées séparées pour les bibliothèques
    .addEntry('vendor', [
        'jquery',
        'bootstrap',
    ])
    
    // SB Admin 2 spécifique
    .addStyleEntry('sb-admin-2', [
        'startbootstrap-sb-admin-2/css/sb-admin-2.min.css',
        'startbootstrap-sb-admin-2/js/sb-admin-2.min.js',
        '@fortawesome/fontawesome-free/css/all.min.css'
    ])
    
    // Features
    .enableStimulusBridge('./assets/controllers.json')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .autoProvidejQuery()
    
    // Configuration Babel
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })
    
    // Copie des assets
    // .copyFiles([
    //     {
    //         from: './node_modules/@fortawesome/fontawesome-free/webfonts',
    //         to: 'webfonts/[name].[hash:8].[ext]'
    //     },
    //     {
    //         from: './assets/images',
    //         to: 'images/[path][name].[hash:8].[ext]'
    //     }
    // ])
;

module.exports = Encore.getWebpackConfig();