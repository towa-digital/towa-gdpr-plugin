const mix = require('laravel-mix');
const path = require('path');
const dotenv = require('dotenv');

require('laravel-mix-purgecss');
dotenv.config({ path: './.env' });

mix
	.setPublicPath('dist')
	.js('src/js/main.js','dist/js')
	.sass('src/scss/main.scss','dist/css')

	.webpackConfig({
		resolve: {
			extensions: ['*', '.js', '.vue', '.json', 'scss', '.css'],
			alias: {
				'@': path.resolve(__dirname),
				'@styles': path.resolve(__dirname, 'src', 'scss')
			}
		},
		output: {
			// The public path needs to be set to the root of the site so
			// Webpack can locate chunks at runtime.
			publicPath: '/dist/',
			// We'll place all chunks in the `js` folder by default so we don't
			// need to worry about ignoring them in our version control system.
			chunkFilename: 'js/[name]-[hash].js',
		}
	})
	.browserSync({
		proxy: process.env.WP_HOME,
		files: [
			'src/**/*.{php,twig}',
			'views/**/*.{php,twig}',
			'dist/js/**/*.js',
			'dist/css/**/*.css',
		],
	});

if (process.env.npm_lifecycle_event !== 'hot') {
	mix.version();
}
