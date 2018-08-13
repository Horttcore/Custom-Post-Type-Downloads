module.exports = {
	entry: {
		'downloads-block': './src/js/downloads-block.js',
		'downloads': './src/js/downloads.js',
		'admin.downloads': './src/js/admin.downloads.js'
	},
	output: {
		path: __dirname,
		filename: 'dist/js/[name].js',
	},
	module: {
		loaders: [
			{
				test: /.js$/,
				loader: 'babel-loader',
				exclude: /node_modules/,
			},
		],
	},
};