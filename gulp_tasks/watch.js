const gulp = require( 'gulp' );
const pkg = require( '../package.json' );
const browserSync = require( 'browser-sync' );
const webpack = require( 'webpack' );
const webpackStream = require( 'webpack-stream' );
const merge = require( 'webpack-merge' );
const webpackAdminDevConfig = require( '../webpack/admindev' );
const webpackThemeDevConfig = require( '../webpack/themedev' );
const watchRules = require( '../webpack/rules/watch' );
const watchPlugins = require( '../webpack/plugins/watch' );

const watchConfig = {
	watch: true,
};

webpackAdminDevConfig.module.rules = watchRules;
webpackThemeDevConfig.module.rules = watchRules;
webpackAdminDevConfig.plugins = watchPlugins.admin;
webpackThemeDevConfig.plugins = watchPlugins.theme;

function maybeReloadBrowserSync() {
	const server = browserSync.get( 'Tribe Dev' );
	if ( server.active ) {
		server.reload();
	}
}

module.exports = {
	frontEndDev() {
		// watch main theme postcss minus admin styles

		gulp.watch( [
			`${ pkg.square1.paths.core_theme_pcss }**/*.pcss`,
			`${ pkg.square1.paths.core_theme_components }**/*.pcss`,
			`${ pkg.square1.paths.core_theme_integrations }**/*.pcss`,
			`!${ pkg.square1.paths.core_theme_pcss }legacy.pcss`,
			`!${ pkg.square1.paths.core_theme_pcss }content/page/_legacy.pcss`,
			`!${ pkg.square1.paths.core_admin_pcss }**/*.pcss`,
		], gulp.parallel( 'postcss:theme' ) );

		// watch the legacy postcss

		gulp.watch( [
			`${ pkg.square1.paths.core_theme_pcss }legacy.pcss`,
			`${ pkg.square1.paths.core_theme_pcss }content/page/_legacy.pcss`,
		], gulp.parallel( 'postcss:themeLegacy' ) );

		// watch the login postcss

		gulp.watch( [
			`${ pkg.square1.paths.core_admin_pcss }login.pcss`,
		], gulp.parallel( 'postcss:themeWPLogin' ) );

		// watch the editor styles postcss

		gulp.watch( [
			`${ pkg.square1.paths.core_admin_pcss }editor-style.pcss`,
		], gulp.parallel( 'postcss:themeWPEditor' ) );

		// watch the admin styles postcss

		gulp.watch( [
			`${ pkg.square1.paths.core_admin_pcss }**/*.pcss`,
			`!${ pkg.square1.paths.core_admin_pcss }editor-style.pcss`,
			`!${ pkg.square1.paths.core_admin_pcss }login.pcss`,
		], gulp.parallel( 'postcss:themeWPAdmin' ) );

		// watch php and twig

		gulp.watch( [
			`${ pkg.square1.paths.core_theme }/**/*.php`,
			`${ pkg.square1.paths.core_theme }/**/*.twig`,
		] ).on( 'change', function() {
			maybeReloadBrowserSync();
		} );
	},
	watchAdminJS() {
		gulp.src( `${ pkg.square1.paths.core_admin_js_src }**/*.js` )
			.pipe( webpackStream( merge( webpackAdminDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_admin_js_dist ) );
	},
	watchThemeJS() {
		gulp.src( `${ pkg.square1.paths.core_theme_js_src }**/*.js` )
			.pipe( webpackStream( merge( webpackThemeDevConfig, watchConfig ), webpack, function( err, stats ) {
				console.log( stats.toString( { colors: true } ) );
				maybeReloadBrowserSync();
			} ) )
			.pipe( gulp.dest( pkg.square1.paths.core_theme_js_dist ) );
	},
};
